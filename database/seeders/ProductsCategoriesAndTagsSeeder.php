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

class ProductsCategoriesAndTagsSeeder extends Seeder
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
                    'Nachhaltig', 'Handgemacht',  'Recycelt', 'Fair Trade', 'Premium', 'Neu',
                    
                    // Kategorie- und Verwendungszweck
                    'Geschenkidee',  'Für Zuhause', 'Outdoor', 'Modeaccessoires', 'Sport & Freizeit',
                    
                    // Stil und Design
                    'Modern', 'Minimalistisch', 'Vintage', 'Rustikal', 'Luxuriös', 'Farbenfroh', 'Einzigartig', 'Klassisch',
                    
                    // Saisonale Tags
                    'Sommer', 'Winter', 'Frühling', 'Herbst', 'Weihnachten', 'Ostern', 'Valentinstag', 'Halloween',
                    
                    // Zielgruppe
                    'Frauen', 'Männer', 'Unisex', 'Kinder', 'Teenager', 'Familie',
                    
                    // Materialien
                    'Holz', 'Metall', 'Glas', 'Kunststoff', 'Baumwolle', 'Leder', 'Keramik',
                    
                    // Funktionale Tags
                    'Platzsparend', 'Praktisch', 'Innovativ', 'Multifunktional', 'Tragbar', 'Leicht', 'Wasserdicht',
                    
                    // Emotionale Tags
                    'Entspannung', 'Abenteuer', 'Kreativität', 'Inspiration', 'Spaß', 'Wohlfühlen',
                    
                    // Preiskategorie
                    'Günstig', 'Mittelklasse', 'Premium', 'Luxus',
                    
                    // Sonstiges
                    'Limited Edition', 'Umweltfreundlich',
                ];
        
                foreach ($tags as $tagName) {
                    Tag::updateOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
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
