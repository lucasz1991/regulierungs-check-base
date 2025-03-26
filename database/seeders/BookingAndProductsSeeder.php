<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Customer;
use App\Models\Location;
use App\Models\RetailSpace;
use App\Models\Product;
use App\Models\Shelve;
use App\Models\ShelfRental;
use App\Models\Like;
use App\Models\Review;
use App\Models\Message;
use App\Models\Setting;
use App\Models\WebContent;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ShelfBlockedDate;
use App\Models\BlockedDate;
use Carbon\Carbon;
use App\Jobs\UpdateBlockedDatesJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


                // categories Seeder
                $categories = [
                    [
                        'name' => 'Kleidung & Accessoires',
                        'slug' => 'kleidung-accessoires',
                        'children' => [
                            [
                                'name' => 'Alltag',
                                'slug' => 'alltag',
                                'children' => [
                                    ['name' => 'Bodys', 'slug' => 'bodys'],
                                    ['name' => 'Hosen', 'slug' => 'hosen'],
                                    ['name' => 'Oberteile', 'slug' => 'oberteile'],
                                    ['name' => 'Pullover', 'slug' => 'pullover'],
                                    ['name' => 'Strampler', 'slug' => 'strampler'],
                                    ['name' => 'Kleider', 'slug' => 'kleider'],
                                    ['name' => 'Röcke', 'slug' => 'roecke'],
                                    ['name' => 'Shorts', 'slug' => 'shorts'],
                                ],
                            ],
                            [
                                'name' => 'Saisonale Kleidung',
                                'slug' => 'saisonale-kleidung',
                                'children' => [
                                    ['name' => 'Regenkleidung', 'slug' => 'regenkleidung'],
                                    ['name' => 'Wintersachen', 'slug' => 'wintersachen'],
                                    ['name' => 'Jacken', 'slug' => 'jacken'],
                                    ['name' => 'Strickjacken', 'slug' => 'strickjacken'],
                                    ['name' => 'Mützen', 'slug' => 'muetzen'],
                                    ['name' => 'Tücher', 'slug' => 'tuecher'],
                                ],
                            ],
                            [
                                'name' => 'Unterwäsche & Nachtwäsche',
                                'slug' => 'unterwaesche-nachtwaesche',
                                'children' => [
                                    ['name' => 'Schlafanzüge', 'slug' => 'schlafanzuege'],
                                    ['name' => 'Socken', 'slug' => 'socken'],
                                    ['name' => 'Strumpfhosen', 'slug' => 'strumpfhosen'],
                                ],
                            ],
                            [
                                'name' => 'Schuhe',
                                'slug' => 'schuhe',
                                'children' => [
                                    ['name' => 'Sportschuhe', 'slug' => 'sportschuhe'],
                                    ['name' => 'Stiefel', 'slug' => 'stiefel'],
                                ],
                            ],
                            [
                                'name' => 'Spezial',
                                'slug' => 'spezial',
                                'children' => [
                                    ['name' => 'Umstandsmode', 'slug' => 'umstandsmode'],
                                    ['name' => 'Schlafsäcke', 'slug' => 'schlafsaecke'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Spielzeug & Kreatives',
                        'slug' => 'spielzeug-kreatives',
                        'children' => [
                            [
                                'name' => 'Markenspielzeug',
                                'slug' => 'markenspielzeug',
                                'children' => [
                                    ['name' => 'Barbie', 'slug' => 'barbie'],
                                    ['name' => 'Lego', 'slug' => 'lego'],
                                    ['name' => 'Playmobil', 'slug' => 'playmobil'],
                                    ['name' => 'Tonie', 'slug' => 'tonie'],
                                    ['name' => 'Schleich', 'slug' => 'schleich'],
                                ],
                            ],
                            [
                                'name' => 'Allgemeines Spielzeug',
                                'slug' => 'allgemeines-spielzeug',
                                'children' => [
                                    ['name' => 'Spielzeug', 'slug' => 'spielzeug'],
                                    ['name' => 'Bücher', 'slug' => 'buecher'],
                                ],
                            ],
                            [
                                'name' => 'Pädagogisches',
                                'slug' => 'paedagogisches',
                                'children' => [
                                    ['name' => 'Bastelsets', 'slug' => 'bastelsets'],
                                    ['name' => 'Lernspielzeug', 'slug' => 'lernspielzeug'],
                                    ['name' => 'Puzzles', 'slug' => 'puzzles'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Mobilität',
                        'slug' => 'mobilitaet',
                        'children' => [
                            ['name' => 'Buggy', 'slug' => 'buggy'],
                            ['name' => 'Kinderwagen', 'slug' => 'kinderwagen'],
                            ['name' => 'Kindersitze', 'slug' => 'kindersitze'],
                            ['name' => 'Tragetücher', 'slug' => 'tragetuecher'],
                        ],
                    ],
                    [
                        'name' => 'Outdoor & Aktivitäten',
                        'slug' => 'outdoor-aktivitaeten',
                        'children' => [
                            [
                                'name' => 'Aktivitäten',
                                'slug' => 'aktivitaeten',
                                'children' => [
                                    ['name' => 'Outdoor-Spielzeug', 'slug' => 'outdoor-spielzeug'],
                                    ['name' => 'Sandkasten-Spielzeug', 'slug' => 'sandkasten-spielzeug'],
                                ],
                            ],
                            [
                                'name' => 'Sport',
                                'slug' => 'sport',
                                'children' => [
                                    ['name' => 'Roller', 'slug' => 'roller'],
                                    ['name' => 'Schlittschuhe', 'slug' => 'schlittschuhe'],
                                    ['name' => 'Fussball', 'slug' => 'fussball'],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Sonstiges',
                        'slug' => 'sonstiges',
                        'children' => [
                            [
                                'name' => 'Geschenke & Dekoratives',
                                'slug' => 'geschenke-dekoratives',
                                'children' => [
                                    ['name' => 'Geburtstagsartikel', 'slug' => 'geburtstagsartikel'],
                                    ['name' => 'Deko für Kinderzimmer', 'slug' => 'deko-fuer-kinderzimmer'],
                                ],
                            ],
                            [
                                'name' => 'Spezialthemen',
                                'slug' => 'spezialthemen',
                                'children' => [
                                    ['name' => 'Handmade', 'slug' => 'handmade'],
                                    ['name' => 'Besondere Accessoires', 'slug' => 'besondere-accessoires'],
                                ],
                            ],
                        ],
                    ],
                ];
        
                $this->createCategories($categories);
        
                $tags = [
                    // Produktmerkmale
                    'Nachhaltig', 'Handgemacht', 'Bio', 'Vegan', 'Recycelt', 'Fair Trade', 'Premium', 'Neu', 'Bestseller',
                    
                    // Kategorie- und Verwendungszweck
                    'Geschenkidee', 'Für Kinder', 'Für Haustiere', 'Für Zuhause', 'Outdoor', 'Bürobedarf', 'Elektronik', 'Modeaccessoires', 'Sport & Freizeit',
                    
                    // Stil und Design
                    'Modern', 'Minimalistisch', 'Vintage', 'Rustikal', 'Luxuriös', 'Farbenfroh', 'Einzigartig', 'Klassisch',
                    
                    // Saisonale Tags
                    'Sommer', 'Winter', 'Frühling', 'Herbst', 'Weihnachten', 'Ostern', 'Valentinstag', 'Halloween',
                    
                    // Zielgruppe
                    'Frauen', 'Männer', 'Unisex', 'Kinder', 'Teenager', 'Senioren', 'Familie',
                    
                    // Materialien
                    'Holz', 'Metall', 'Glas', 'Kunststoff', 'Baumwolle', 'Leder', 'Keramik',
                    
                    // Funktionale Tags
                    'Platzsparend', 'Praktisch', 'Innovativ', 'Multifunktional', 'Tragbar', 'Leicht', 'Wasserdicht',
                    
                    // Emotionale Tags
                    'Entspannung', 'Abenteuer', 'Kreativität', 'Inspiration', 'Spaß', 'Wohlfühlen',
                    
                    // Preiskategorie
                    'Günstig', 'Mittelklasse', 'Premium', 'Luxus',
                    
                    // Sonstiges
                    'Limited Edition', 'Personalisierbar', 'DIY (Do it yourself)', 'Sofort verfügbar', 'Umweltfreundlich',
                ];
        
                foreach ($tags as $tagName) {
                    Tag::updateOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
                }




        
        $specificDate = '2025-01-25';
        $shelves = Shelve::where('retail_space_id', 1)->get();

                        foreach  ($shelves as $shelf) {
                                $rentalStart = Carbon::parse($specificDate); // Startdatum
                                $rentalPeriod = fake()->randomElement([7, 14, 21]); // Mietdauer in Tagen
                                $currentDate = $rentalStart->copy(); // Kopie des Startdatums für die Berechnung
                                $daysAdded = 0;
                                $totalDays = 0; // Zählt die Gesamttage inklusive ausgeschlossener Sonntage
                                
                                // Berechnung der Gesamttage unter Ausschluss von Sonntagen
                                while ($daysAdded < $rentalPeriod) {
                                    $currentDate->addDay();
                                    $totalDays++;
                                    
                                    if (!$currentDate->isSunday()) {
                                        $daysAdded++;
                                    }
                                }
                                
                                // Erstellung des Modify-Strings basierend auf den Gesamttagen
                                $modifyString = '+' . $totalDays . ' days';
                                
                                // Beispiel für die Berechnung des Enddatums
                                $rentalEnd = (clone $rentalStart)->modify($modifyString);
                                // Verfügbare Regale filtern
                                $availableShelf = Shelve::where('id', $shelf->id)->whereDoesntHave('blockedDates', function ($query) use ($rentalStart, $rentalEnd) {
                                    $query->whereBetween('blocked_date', [$rentalStart, $rentalEnd]);
                                })->inRandomOrder()->first();

                                if (!$availableShelf) {
                                    // Kein verfügbares Regal gefunden
                                    logger()->warning('No available shelf found for the given period.', [
                                        'rental_start' => $rentalStart,
                                        'rental_end' => $rentalEnd,
                                    ]);
                                    continue; // Abbrechen, wenn kein Regal verfügbar ist
                                }
                                // Überprüfen, ob die Miete aktuell ist
                                $status = Carbon::now()->between($rentalStart, $rentalEnd) ? 2 : 1;

                                // Feste Mietdauer

                                switch ($rentalPeriod) {
                                    case 7:
                                        $weeklyRate = 25; // Für 7 Tage
                                        break;
                                    case 14:
                                        $weeklyRate = 45; // Für 14 Tage
                                        break;
                                    case 21:
                                        $weeklyRate = 60; // Für 21 Tage
                                        break;
                                    default:
                                        $weeklyRate = 0; // Standardwert, falls die Periode nicht zutrifft
                                        break;
                                }
                                $totalPrice = $weeklyRate;

                                    // ShelfRental erstellen
                                    $shelfRental = ShelfRental::create([
                                        'shelf_id' => $shelf->id,
                                        'customer_id' => Customer::inRandomOrder()->first()->id, // Zufälliger Kunde
                                        'rental_start' => $rentalStart,
                                        'rental_end' => $rentalEnd,
                                        'total_price' => $totalPrice,
                                        'payment_method' => fake()->randomElement(['Credit Card', 'PayPal', 'Bank Transfer', 'Cash']),
                                        'period' => $rentalPeriod, // Immer 7 Tage
                                        'status' => $status,
                                        'rental_bill_url' => "-",
                                        'complete_bill_url' => "-",
                                    ]);


                                    // Blockierte Tage aktualisieren
                                    UpdateBlockedDatesJob::dispatch($shelfRental);
                            }



        $rentalCount = 500;
        for ($i = 0; $i < $rentalCount; $i++) {
                                $rentalStart = fake()->dateTimeBetween('-7 days', '+120 days');
                                $rentalPeriod = fake()->randomElement([7, 14, 21]); // Mietdauer in Tagen
                                $currentDate = Carbon::parse($rentalStart); // Kopie des Startdatums für die Berechnung
                                $daysAdded = 0;
                                $totalDays = 0; // Zählt die Gesamttage inklusive ausgeschlossener Sonntage
                                
                                // Berechnung der Gesamttage unter Ausschluss von Sonntagen
                                while ($daysAdded < $rentalPeriod) {
                                    $currentDate->addDay();
                                    $totalDays++;
                                    
                                    if (!$currentDate->isSunday()) {
                                        $daysAdded++;
                                    }
                                }
                                
                                // Erstellung des Modify-Strings basierend auf den Gesamttagen
                                $modifyString = '+' . $totalDays . ' days';
                                
                                // Beispiel für die Berechnung des Enddatums
                                $rentalEnd = (clone $rentalStart)->modify($modifyString);

            // Überprüfen, ob die Miete aktuell ist
            $status = Carbon::now()->between($rentalStart, $rentalEnd) ? 2 : 1;

            // Feste Mietdauer

            switch ($rentalPeriod) {
                case 7:
                    $weeklyRate = 25; // Für 7 Tage
                    break;
                case 14:
                    $weeklyRate = 45; // Für 14 Tage
                    break;
                case 21:
                    $weeklyRate = 60; // Für 21 Tage
                    break;
                default:
                    $weeklyRate = 0; // Standardwert, falls die Periode nicht zutrifft
                    break;
            }
            $totalPrice = $weeklyRate;

                // Verfügbare Regale filtern
                $availableShelf = Shelve::whereDoesntHave('blockedDates', function ($query) use ($rentalStart, $rentalEnd) {
                    $query->whereBetween('blocked_date', [$rentalStart, $rentalEnd]);
                })->inRandomOrder()->first();

                if (!$availableShelf) {
                    // Kein verfügbares Regal gefunden
                    logger()->warning('No available shelf found for the given period.', [
                        'rental_start' => $rentalStart,
                        'rental_end' => $rentalEnd,
                    ]);
                    continue; // Abbrechen, wenn kein Regal verfügbar ist
                }

                // ShelfRental erstellen
                $shelfRental = ShelfRental::create([
                    'shelf_id' => $availableShelf->id,
                    'customer_id' => Customer::inRandomOrder()->first()->id, // Zufälliger Kunde
                    'rental_start' => $rentalStart,
                    'rental_end' => $rentalEnd,
                    'total_price' => $totalPrice,
                    'payment_method' => fake()->randomElement(['Credit Card', 'PayPal', 'Bank Transfer', 'Cash']),
                    'period' => $rentalPeriod, // Immer 7 Tage
                    'status' => $status,
                    'rental_bill_url' => "",
                    'complete_bill_url' => "",
                ]);
                // Blockierte Tage aktualisieren
                UpdateBlockedDatesJob::dispatch($shelfRental);
        }


                // Lade alle Kategorien als Collection
                $categories = collect(Category::all());

                $productNamesByCategory = [
                    // Kleidung & Accessoires
                    'Kleidung & Accessoires' => ['Sommerkollektion', 'Winterkleidung Set', 'Kinderaccessoires Bundle'],
                    'Alltag' => ['Tägliche Outfits', 'Kinder-Basics', 'Alltags-Mode'],
                    'Bodys' => ['Baumwoll-Bodys', 'Langarm-Bodys', 'Bio-Bodys'],
                    'Hosen' => ['Kinderjeans', 'Leggings', 'Sommerhosen'],
                    'Oberteile' => ['T-Shirts', 'Poloshirts', 'Langarmshirts'],
                    'Pullover' => ['Strickpullover', 'Sweatshirts', 'Kapuzenpullis'],
                    'Strampler' => ['Babyschlafstrampler', 'Winterstrampler', 'Sommerstrampler'],
                    'Kleider' => ['Blumenkleider', 'Festliche Kleider', 'Strandkleider'],
                    'Röcke' => ['Tüllröcke', 'Jeansröcke', 'Plisseeröcke'],
                    'Shorts' => ['Sommer-Shorts', 'Cargo-Shorts', 'Stretch-Shorts'],
                    'Saisonale Kleidung' => ['Regenjackenset', 'Winterkleidungspaket', 'Herbstkollektion'],
                    'Regenkleidung' => ['Regenjacken', 'Regenhosen', 'Gummistiefel'],
                    'Wintersachen' => ['Winterjacken', 'Schneeanzüge', 'Thermohosen'],
                    'Jacken' => ['Jeansjacken', 'Windbreaker', 'Übergangsjacken'],
                    'Strickjacken' => ['Cardigans', 'Grober Strick', 'Leichter Strick'],
                    'Mützen' => ['Wollmützen', 'Sommerhüte', 'Caps'],
                    'Tücher' => ['Schals', 'Halstücher', 'Musselintücher'],
                    'Unterwäsche & Nachtwäsche' => ['Nachtwäsche Sets', 'Schlaf- und Unterwäsche', 'Kinderwäsche Kollektion'],
                    'Schlafanzüge' => ['Baumwoll-Schlafanzüge', 'Kurzarm-Schlafanzüge', 'Langarm-Schlafanzüge'],
                    'Socken' => ['Anti-Rutsch-Socken', 'Sportsocken', 'Baumwollsöckchen'],
                    'Strumpfhosen' => ['Thermostrumpfhosen', 'Baumwollstrumpfhosen', 'Festliche Strumpfhosen'],
                    'Schuhe' => ['Kinder-Schuhkollektion', 'Outdoor-Schuhe', 'Sport- und Alltagsschuhe'],
                    'Sportschuhe' => ['Laufschuhe', 'Sneakers', 'Hallenschuhe'],
                    'Stiefel' => ['Winterstiefel', 'Gummistiefel', 'Lederstiefel'],
                    'Spezial' => ['Spezielles Zubehör', 'Einzigartige Kollektionen', 'Limitierte Artikel'],
                    'Umstandsmode' => ['Still-Tops', 'Umstandshosen', 'Umstandskleider'],
                    'Schlafsäcke' => ['Winter-Schlafsäcke', 'Sommer-Schlafsäcke', 'Mitwachsender Schlafsack'],
                
                    // Spielzeug & Kreatives
                    'Spielzeug & Kreatives' => ['Spielzeugpaket', 'Kreativsets', 'Markenspielzeug Bundle'],
                    'Markenspielzeug' => ['Beliebte Markenartikel', 'Kinderfavoriten', 'Markenspielzeug Sets'],
                    'Barbie' => ['Barbie Dreamhouse', 'Barbie Fashionistas', 'Barbie Auto'],
                    'Lego' => ['Lego Technik', 'Lego Friends', 'Lego City'],
                    'Playmobil' => ['Playmobil Feuerwehr', 'Playmobil Bauernhof', 'Playmobil Polizei'],
                    'Tonie' => ['Toniebox', 'Hörspielfiguren', 'Tonie Tragetasche'],
                    'Schleich' => ['Schleich Pferde', 'Schleich Bauernhof', 'Schleich Dinosaurier'],
                    'Allgemeines Spielzeug' => ['Klassisches Spielzeug', 'Freizeitspaß', 'Spielzeugideen'],
                    'Spielzeug' => ['Holzspielzeug', 'Puppenhäuser', 'Bausteine'],
                    'Bücher' => ['Kinderbücher', 'Bilderbücher', 'Märchenbücher'],
                    'Pädagogisches' => ['Bildungsprodukte', 'Lern- und Spielspaß', 'Lernhilfen'],
                    'Bastelsets' => ['Perlen-Sets', 'Malen nach Zahlen', 'Tonmodellierung'],
                    'Lernspielzeug' => ['Zahlenpuzzle', 'ABC-Blöcke', 'Konstruktionsspielzeug'],
                    'Puzzles' => ['Holzpuzzles', '3D-Puzzles', '100-Teile-Puzzles'],
                
                    // Mobilität
                    'Mobilität' => ['Reiseaccessoires', 'Kinderwagen-Extras', 'Unterwegs mit Kindern'],
                    'Buggy' => ['Leichter Buggy', 'Reisebuggy', 'Sportbuggy'],
                    'Kinderwagen' => ['Kombikinderwagen', 'Zwillingskinderwagen', 'Jogger-Kinderwagen'],
                    'Kindersitze' => ['Babyschale', 'Mitwachsender Autositz', 'Kindersitz Gruppe 2/3'],
                    'Tragetücher' => ['Elastische Tragetücher', 'Gewebte Tragetücher', 'Ringslings'],
                
                    // Outdoor & Aktivitäten
                    'Outdoor & Aktivitäten' => ['Freizeitspaß', 'Draußen spielen', 'Sport & Bewegung'],
                    'Aktivitäten' => ['Bewegungsspaß', 'Abenteuer draußen', 'Kreative Aktivitäten'],
                    'Outdoor-Spielzeug' => ['Wasserpistolen', 'Wurfspiele', 'Springseile'],
                    'Sandkasten-Spielzeug' => ['Sandförmchen', 'Schaufeln', 'Eimer-Sets'],
                    'Sport' => ['Kindersportartikel', 'Outdoor-Ausrüstung', 'Aktive Freizeit'],
                    'Roller' => ['Kinderroller', 'Klapproller', 'Stunt-Scooter'],
                    'Schlittschuhe' => ['Kinder-Schlittschuhe', 'Verstellbare Schlittschuhe', 'Eiskunstlauf-Schlittschuhe'],
                    'Fussball' => ['Kinderfußbälle', 'Mini-Tore', 'Trainingszubehör'],
                
                    // Sonstiges
                    'Sonstiges' => ['Dekorationsideen', 'Geschenkideen', 'Spezialartikel'],
                    'Geschenke & Dekoratives' => ['Dekopaket', 'Kinderzimmerartikel', 'Geschenkboxen'],
                    'Geburtstagsartikel' => ['Kerzen', 'Partyhüte', 'Luftballons'],
                    'Deko für Kinderzimmer' => ['Wandsticker', 'Nachtlichter', 'Kinderteppiche'],
                    'Spezialthemen' => ['Handmade Produkte', 'Personalisierte Artikel', 'Selbstgemachte Accessoires'],
                    'Handmade' => ['Gestrickte Puppen', 'Handgenähte Kissen', 'Holzspielzeug'],
                    'Besondere Accessoires' => ['Personalisierte Anhänger', 'Lederportemonnaies', 'Unikate'],
                ];

            for ($i = 0; $i < 3000; $i++) {
                // Zufällige Kategorie auswählen
                $randomCategory = $categories->random();
                $categoryName = $randomCategory->name;
                $categorySlug = $randomCategory->slug;

                // Passenden Namen aus den Vorlagen generieren
                $productName = $productNamesByCategory[$categoryName][array_rand($productNamesByCategory[$categoryName])] ?? fake()->words(3, true);
                // Zufällige Miete auswählen
                $shelfRental = ShelfRental::inRandomOrder()->first();

                // Produktstatus entsprechend der zugewiesenen Miete setzen
                $status = $shelfRental->status;
                // Produkt erstellen
                $product = Product::create([
                    'customer_id' => Customer::inRandomOrder()->first()->id,
                    'shelf_rental_id' => $shelfRental->id,
                    'name' => $productName,
                    'price' => fake()->randomFloat(2, 5, 500),
                    'description' => fake()->paragraph,
                    'images' => json_encode([fake()->numberBetween(1, 8)]),
                    'category' => $categorySlug,
                    'tags' => json_encode(Tag::inRandomOrder()->take(rand(1, 5))->pluck('id')->toArray()),
                    'age_recommendation' => fake()->randomElement(['ab 6 Monaten', 'ab 1 Jahr', 'ab 3 Jahren', 'ab 6 Jahren']),
                    'status' =>  $status,
                    'views' => fake()->numberBetween(0, 100),
                    'published_at' => fake()->dateTimeBetween('-4 days', '-1 days'),
                ]);

                // Zufällige Anzahl von Tags (0 bis 5) auswählen
                $randomTags = collect($tags)->random(rand(0, 5));

                // Tags dem Produkt zuweisen
                foreach ($randomTags as $tagName) {
                    $tag = Tag::firstWhere('name', $tagName);
                    if ($tag) {
                        $product->tags()->attach($tag->id);
                    }
                }
            }
    }


    private function createCategories(array $categories, $parentId = null)
    {
        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = Category::create(array_merge($categoryData, [
                'parent_id' => $parentId,
            ]));

            if (!empty($children)) {
                $this->createCategories($children, $category->id);
            }
        }
    }
}
