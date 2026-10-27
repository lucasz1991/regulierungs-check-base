"""Build the isolated homepage preview; never boots Laravel or touches MySQL.

Only the fixed new target below may be written. Existing targets are refused.
The source fixture and public snapshot are opened read-only and hashed.
Snapshot aggregates remain metadata: no synthetic review rows are manufactured
to imitate the public totals. Rating questionnaires are explicitly empty preview
scaffolds, since the public snapshot does not contain the production questions.
"""
import datetime
import hashlib
import html
import json
import pathlib
import re
import sqlite3
import unicodedata
from urllib.parse import urlparse

BASE = pathlib.Path(r"C:\xampp\htdocs\regulierungs-check\regulierungs-check-base").resolve()
SOURCE = BASE.parent / ".lmzdev/artifacts/temp/promotion-v2-screenshots.sqlite"
TARGET = BASE / ".lmzdev/artifacts/temp/homepage-redesign-2026-10-24.sqlite"
REPORTS = pathlib.Path(r"C:\Users\L. Zacharias\Documents\Regulierungs-CHECK-Redesign\.lmzdev\artifacts\reports")
SNAPSHOT = REPORTS / "live-content-v3.json"
CATEGORIES = REPORTS / "live-categories-v3.json"
DESIGN = REPORTS.parents[2] / 'project-design/index.html'

def digest(path):
    return hashlib.sha256(path.read_bytes()).hexdigest()

def encoded(value):
    return json.dumps(value, ensure_ascii=False)

def slug(value):
    value = value.replace('ß', 'ss')
    return re.sub(r'[^a-z0-9]+', '-', unicodedata.normalize('NFKD', value).encode('ascii', 'ignore').decode().lower()).strip('-')

def approved_about_html():
    source = DESIGN.read_text(encoding='utf-8')
    match = re.search(r'<section\b(?=[^>]*\bid="plattform")[\s\S]*?</section>', source)
    if match is None:
        raise RuntimeError('Approved section#plattform is missing.')
    section = match.group(0)
    section = section.replace('assets/images/about-platform.png', '/site-images/AdobeStock_507825033-illu-2.png')
    section = section.replace('https://www.regulierungs-check.de/insurances', '/insurances')
    section = re.sub(r'\s+(?:target="_blank"|rel="noopener")', '', section)
    if 'src="assets/' in section or 'https://' in section:
        raise RuntimeError('Unexpected external or relative asset in approved platform section.')
    return section

def main():
    if pathlib.Path(__file__).resolve() != BASE / '.lmzdev/scripts/build-homepage-preview.py':
        raise SystemExit('Unexpected script location; refusing.')
    if TARGET.resolve() != BASE / '.lmzdev/artifacts/temp/homepage-redesign-2026-10-24.sqlite':
        raise SystemExit('Target path guard failed.')
    if TARGET.exists() or TARGET.is_symlink():
        raise SystemExit('Target already exists; refusing overwrite. Keep the existing preview.')
    if not SOURCE.is_file() or SOURCE.resolve() == TARGET.resolve():
        raise SystemExit('Read-only source fixture missing or unsafe.')
    source_hash = digest(SOURCE)
    data = json.loads(SNAPSHOT.read_text(encoding='utf-8'))
    categories = json.loads(CATEGORIES.read_text(encoding='utf-8'))
    stamp = data['source']['snapshot_date'] + ' 12:00:00'
    TARGET.parent.mkdir(parents=True, exist_ok=True)
    # Exclusive creation eliminates accidental overwrite, including concurrent runs.
    with TARGET.open('xb') as output:
        output.write(SOURCE.read_bytes())
    db = sqlite3.connect(TARGET)
    db.execute('PRAGMA foreign_keys = ON')

    def insert(table, values):
        columns = ','.join('"' + key + '"' for key in values)
        marks = ','.join('?' for _ in values)
        return db.execute(f'INSERT INTO "{table}" ({columns}) VALUES ({marks})', list(values.values())).lastrowid

    def setting(kind, key, value):
        db.execute('DELETE FROM settings WHERE type=? AND key=?', (kind, key))
        insert('settings', {'type': kind, 'key': key, 'value': encoded(value), 'created_at': stamp, 'updated_at': stamp})

    with db:
        # Existing synthetic identities stay inactive; no inherited login/session works.
        db.execute("UPDATE users SET status=0, password='!preview-login-disabled', remember_token=NULL")
        for table in ['sessions', 'jobs', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens']:
            db.execute(f'DELETE FROM "{table}"')
        groups, subtypes = {}, {}
        for position, group in enumerate(categories['groups']):
            gid = group['id']
            groups[gid] = group
            insert('insurance_types', {'id': gid, 'name': group['title'], 'slug': slug(group['title']), 'is_active': 1, 'order_column': position, 'created_at': stamp, 'updated_at': stamp})
            for order, child in enumerate(group['children']):
                sid = child['id']
                name = child.get('title') or child['visible_label']
                if sid not in subtypes:
                    route_slug = urlparse(child.get('href') or '').path.rsplit('/', 1)[-1]
                    insert('insurance_subtypes', {'id': sid, 'name': name, 'slug': route_slug or slug(name) + '-' + str(sid), 'is_active': 1, 'order_column': order, 'description': 'Öffentlicher Kategorien-Snapshot vom 19.10.2026.', 'created_at': stamp, 'updated_at': stamp})
                    insert('rating_questionnaire_versions', {'insurance_subtype_id': sid, 'version_number': 1, 'snapshot': '[]', 'is_active': 1, 'created_at': stamp, 'updated_at': stamp})
                    subtypes[sid] = {'name': name, 'group': gid, 'href': child.get('href')}
                insert('insurance_type_insurance_subtype', {'insurance_type_id': gid, 'insurance_subtype_id': sid, 'order_id': order})

        providers = {p['slug']: p for p in data['providers']}
        for review in data['reviews']:
            providers.setdefault(review['insurer_slug'], {'slug': review['insurer_slug'], 'name': review['insurer_name'], 'logo': review.get('insurer_logo', {}), 'name_source': review.get('insurer_name_source', {})})
        provider_ids = {}
        for position, provider in enumerate(providers.values()):
            logo_path = urlparse(provider.get('logo', {}).get('url') or '').path
            logo = logo_path.split('/storage/', 1)[-1] if '/storage/' in logo_path else None
            provider_ids[provider['slug']] = insert('insurances', {'name': provider['name'], 'slug': provider['slug'], 'description': provider.get('name_source', {}).get('evidence_text'), 'logo': logo, 'initials': provider['name'][:2], 'is_active': 1, 'order_column': position, 'created_at': stamp, 'updated_at': stamp})

        imported_reviews = 0
        skipped_reviews = []
        for review in data['reviews']:
            matches = [(sid, entry) for sid, entry in subtypes.items() if entry['href'] == review.get('category_href') or entry['name'] == review['category']]
            if not matches:
                skipped_reviews.append(review['href'])
                continue
            sid, subtype = matches[0]
            rid = int(urlparse(review['href']).path.rsplit('/', 1)[-1])
            date = datetime.datetime.strptime(review['date'], '%d.%m.%Y').strftime('%Y-%m-%d 12:00:00')
            uid = insert('users', {'name': review['author'], 'email': f'public-snapshot-{rid}@example.invalid', 'password': '!preview-login-disabled', 'role': 'guest', 'status': 0, 'privacy_settings': encoded({'ratings': {'name_visibility': 'all'}}), 'created_at': date, 'updated_at': stamp})
            iid = provider_ids[review['insurer_slug']]
            insert('claim_ratings', {'id': rid, 'user_id': uid, 'insurance_id': iid, 'insurance_subtype_id': sid, 'insurance_type_id': subtype['group'], 'answers': '{}', 'attachments': encoded({'scorings': {'ai_overall_comment': review['text']}}), 'rating_score': review['score'] / 5, 'status': 'published', 'is_public': 1, 'data': encoded({'preview_snapshot': {'url': review['href'], 'date': data['source']['snapshot_date'], 'partial': True}}), 'created_at': date, 'updated_at': stamp})
            # Only relationships evidenced by an actual public review are imported.
            if not db.execute('SELECT 1 FROM insurance_insurance_type WHERE insurance_id=? AND insurance_type_id=?', (iid, subtype['group'])).fetchone():
                insert('insurance_insurance_type', {'insurance_id': iid, 'insurance_type_id': subtype['group']})
            imported_reviews += 1

        editor = db.execute('SELECT MIN(id) FROM users').fetchone()[0]
        news_categories = {}
        for news in data['news']:
            category = news.get('category') or 'News'
            if category not in news_categories:
                existing_category = db.execute('SELECT id FROM news_categories WHERE slug=?', (slug(category),)).fetchone()
                news_categories[category] = existing_category[0] if existing_category else insert('news_categories', {'name': category, 'slug': slug(category)})
            image_path = urlparse(news.get('image_url') or '').path
            cover = image_path.split('/storage/', 1)[-1] if '/storage/' in image_path else None
            body = '<p>' + html.escape(news['excerpt']) + '</p><p>Vorschau aus dem öffentlichen Snapshot vom 19.10.2026. Der vollständige Artikel ist in dieser lokalen Datenbasis nicht enthalten.</p><p><a href="' + html.escape(news['href'], quote=True) + '">Originalartikel öffnen</a></p>'
            insert('posts', {'type': 'news', 'title': news['title'], 'slug': urlparse(news['href']).path.rsplit('/', 1)[-1], 'excerpt': news['excerpt'], 'body': body, 'cover_image': cover, 'user_id': editor, 'published': 1, 'published_at': news['date'] + ' 12:00:00', 'news_category_id': news_categories[category], 'created_at': stamp, 'updated_at': stamp})

        # Only the brand-new isolated DB receives this approved CMS section.
        about_html = approved_about_html()
        insert('pagebuilder_projects', {'name': 'Öffentlicher Plattformtext – Snapshot 19.10.2026', 'data': '{}', 'html': about_html, 'cleaned_html': about_html, 'position': encoded(['content_between_1']), 'page': encoded(['start']), 'status': 1, 'type': 'module', 'created_at': stamp, 'updated_at': stamp})
        setting('webcontent', 'news_enabled', True)
        setting('homepage_preview', 'snapshot', data)
        setting('homepage_preview', 'provenance', {'source': data['source'], 'source_fixture_sha256': source_hash, 'snapshot_sha256': digest(SNAPSHOT), 'categories_sha256': digest(CATEGORIES), 'partial_data': True, 'questionnaires': 'Empty preview scaffolds; production questionnaires unavailable.', 'aggregates': 'Public totals retained in snapshot metadata only. Imported review subset must not be represented as complete.', 'skipped_reviews': skipped_reviews})

    counts = {table: db.execute(f'SELECT COUNT(*) FROM "{table}"').fetchone()[0] for table in ['migrations', 'insurances', 'insurance_types', 'insurance_subtypes', 'claim_ratings', 'posts', 'pagebuilder_projects', 'rating_questionnaire_versions']}
    integrity = db.execute('PRAGMA integrity_check').fetchone()[0]
    foreign_keys = list(db.execute('PRAGMA foreign_key_check'))
    db.close()
    if digest(SOURCE) != source_hash:
        raise RuntimeError('Source fixture changed concurrently.')
    print(encoded({'target': str(TARGET), 'bytes': TARGET.stat().st_size, 'sha256': digest(TARGET), 'counts': counts, 'integrity': integrity, 'foreign_key_issues': foreign_keys, 'snapshot_date': data['source']['snapshot_date'], 'partial_data': True, 'skipped_reviews': skipped_reviews}))

if __name__ == '__main__':
    main()
