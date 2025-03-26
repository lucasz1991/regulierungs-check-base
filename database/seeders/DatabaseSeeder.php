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
use App\Models\Bonus;
use App\Models\ShelfBlockedDate;
use App\Models\BlockedDate;
use Carbon\Carbon;
use App\Jobs\UpdateBlockedDatesJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Location Seeder
        $location = Location::create([
            'name' => 'Kanalstraße',
            'address' => 'Kanalstraße 14 A',
            'city' => 'Hamburg',
            'state' => 'Hamburg',
            'postal_code' => '22085',
            'country' => 'Deutschland',
            'phone_number' => '',
            'status' => '2',
            'published_at' => now(),
        ]);

        // RetailSpace Seeder
        $retailSpace = RetailSpace::create([
            'location_id' => 1,
            'name' => 'Erdgeschoss',
            'layout' => '{"dimensions":{"width":"1974","height":"1868"},"backgroundimg":{"url":"http:\/\/127.0.0.1:8000\/storage\/backgrounds\/HQbQt7nqmNobBWTCdpZwxWAILw9teOGB3m0PzFAk.svg","size":"contain"},"elements":{"others":[],"shelves":[{"element_id":1213,"x":232.955591634,"y":106.37644608623,"width":90,"height":50,"text":"1","color":"#09bf11"},{"element_id":1214,"x":328.89278892137,"y":106.384856115,"width":90,"height":50,"text":"2","color":"#09bf11"},{"element_id":1215,"x":426.15940335003,"y":106.80757882946,"width":90,"height":50,"text":"3","color":"#09bf11"},{"element_id":1216,"x":521.69395342348,"y":106.51315440474,"width":90,"height":50,"text":"4","color":"#09bf11"},{"element_id":1217,"x":618.67270694019,"y":106.31690279914,"width":90,"height":50,"text":"5","color":"#09bf11"},{"element_id":1218,"x":763.35694996575,"y":107.11215877972,"width":90,"height":50,"text":"6","color":"#09bf11"},{"element_id":1219,"x":856.89935510035,"y":106.90498258377,"width":90,"height":50,"text":"7","color":"#09bf11"},{"element_id":1220,"x":950.86813750607,"y":106.90498258378,"width":90,"height":50,"text":"8","color":"#09bf11"},{"element_id":1221,"x":1043.9103173065,"y":106.90498258377,"width":90,"height":50,"text":"9","color":"#09bf11"},{"element_id":1222,"x":1139.1378176279,"y":106.90498258377,"width":90,"height":50,"text":"10","color":"#09bf11"},{"element_id":1223,"x":335.32283965839,"y":272.22633404288,"width":90,"height":50,"text":"11","color":"#09bf11"},{"element_id":1224,"x":431.4788901129,"y":272.98827929339,"width":90,"height":50,"text":"12","color":"#09bf11"},{"element_id":1225,"x":527.41576823147,"y":272.98827929339,"width":90,"height":50,"text":"13","color":"#09bf11"},{"element_id":1226,"x":621.38455063718,"y":272.9882792934,"width":90,"height":50,"text":"14","color":"#09bf11"},{"element_id":1227,"x":731.09809874578,"y":272.9882792934,"width":90,"height":50,"text":"15","color":"#09bf11"},{"element_id":1228,"x":825.16869921468,"y":272.9882792934,"width":90,"height":50,"text":"16","color":"#09bf11"},{"element_id":1229,"x":920.77346202282,"y":272.9882792934,"width":90,"height":50,"text":"17","color":"#09bf11"},{"element_id":1230,"x":1024.5827229928,"y":272.98827929339,"width":90,"height":50,"text":"18","color":"#09bf11"},{"element_id":1231,"x":1129.5724474796,"y":272.98827929339,"width":90,"height":50,"text":"19","color":"#09bf11"},{"element_id":1232,"x":431.3904421674,"y":328.09539644643,"width":90,"height":50,"text":"20","color":"#09bf11"},{"element_id":1233,"x":527.41576823147,"y":327.62094268472,"width":90,"height":50,"text":"21","color":"#09bf11"},{"element_id":1234,"x":620.45794803186,"y":327.62094268472,"width":90,"height":50,"text":"22","color":"#09bf11"},{"element_id":1235,"x":731.09809874578,"y":327.62094268472,"width":90,"height":50,"text":"23","color":"#09bf11"},{"element_id":1236,"x":824.83658390425,"y":327.62094268472,"width":90,"height":50,"text":"24","color":"#09bf11"},{"element_id":1237,"x":920.77346202282,"y":327.62094268472,"width":90,"height":50,"text":"25","color":"#09bf11"},{"element_id":1238,"x":1128.8917957745387,"y":327.62094268472,"width":90,"height":50,"text":"26","color":"#09bf11"},{"element_id":1239,"x":1224.8520573219012,"y":282.35438216615523,"width":50,"height":90,"text":"27","color":"#09bf11"},{"element_id":1240,"x":303.84586444889,"y":470.0037104596,"width":50,"height":90,"text":"28","color":"#09bf11"},{"element_id":1241,"x":360.83159681904,"y":463.1099478952,"width":90,"height":50,"text":"29","color":"#09bf11"},{"element_id":1242,"x":454.80037922475,"y":463.1099478952,"width":90,"height":50,"text":"30","color":"#09bf11"},{"element_id":1243,"x":548.76916163046,"y":463.1099478952,"width":90,"height":50,"text":"31","color":"#09bf11"},{"element_id":1244,"x":642.73794403618,"y":463.1099478952,"width":90,"height":50,"text":"32","color":"#09bf11"},{"element_id":1245,"x":735.78012383657,"y":463.1099478952,"width":90,"height":50,"text":"33","color":"#09bf11"},{"element_id":1246,"x":830.6755088476,"y":463.1099478952,"width":90,"height":50,"text":"34","color":"#09bf11"},{"element_id":1247,"x":924.64429125332,"y":463.1099478952,"width":90,"height":50,"text":"35","color":"#09bf11"},{"element_id":1248,"x":1018.613073659,"y":463.1099478952,"width":90,"height":50,"text":"36","color":"#09bf11"},{"element_id":1249,"x":1111.6552534594,"y":463.1099478952,"width":90,"height":50,"text":"37","color":"#09bf11"},{"element_id":1250,"x":360.83159681904,"y":517.74261128652,"width":90,"height":50,"text":"38","color":"#09bf11"},{"element_id":1251,"x":454.80037922475,"y":517.74261128652,"width":90,"height":50,"text":"39","color":"#09bf11"},{"element_id":1252,"x":548.76916163046,"y":517.74261128652,"width":90,"height":50,"text":"40","color":"#09bf11"},{"element_id":1253,"x":642.73794403618,"y":517.74261128652,"width":90,"height":50,"text":"41","color":"#09bf11"},{"element_id":1254,"x":736.70672644189,"y":517.74261128652,"width":90,"height":50,"text":"42","color":"#09bf11"},{"element_id":1255,"x":830.42179800397,"y":517.48059431164,"width":90,"height":50,"text":"43","color":"#09bf11"},{"element_id":1256,"x":923.717688648,"y":517.74261128652,"width":90,"height":50,"text":"44","color":"#09bf11"},{"element_id":1257,"x":1018.613073659,"y":517.74261128652,"width":90,"height":50,"text":"45","color":"#09bf11"},{"element_id":1258,"x":1112.5818560647,"y":517.74261128652,"width":90,"height":50,"text":"46","color":"#09bf11"},{"element_id":1259,"x":452.8322835118886,"y":679.45529492484,"width":90,"height":50,"text":"47","color":"#09bf11"},{"element_id":1260,"x":547.84255902514,"y":679.45529492484,"width":90,"height":50,"text":"48","color":"#09bf11"},{"element_id":1261,"x":642.73794403617,"y":679.45529492484,"width":90,"height":50,"text":"49","color":"#09bf11"},{"element_id":1262,"x":737.36611083005,"y":680.11467509313,"width":90,"height":50,"text":"50","color":"#09bf11"},{"element_id":1263,"x":832.86082936866,"y":679.45529492484,"width":90,"height":50,"text":"51","color":"#09bf11"},{"element_id":1264,"x":926.82961177437,"y":679.45529492484,"width":90,"height":50,"text":"52","color":"#09bf11"},{"element_id":1265,"x":450.86418779902715,"y":734.08795831616,"width":90,"height":50,"text":"53","color":"#09bf11"},{"element_id":1266,"x":548.3384537540887,"y":732.1198469892659,"width":90,"height":50,"text":"54","color":"#09bf11"},{"element_id":1267,"x":642.28294339325,"y":734.08795831616,"width":90,"height":50,"text":"55","color":"#09bf11"},{"element_id":1268,"x":736.70672644189,"y":734.08795831616,"width":90,"height":50,"text":"56","color":"#09bf11"},{"element_id":1269,"x":830.6755088476,"y":734.08795831616,"width":90,"height":50,"text":"57","color":"#09bf11"},{"element_id":1270,"x":924.64429125331,"y":734.08795831616,"width":90,"height":50,"text":"58","color":"#09bf11"},{"element_id":1271,"x":486.0590971405,"y":989.75592625558,"width":50,"height":90,"text":"59","color":"#09bf11"},{"element_id":1272,"x":540.02787954621,"y":981.02759684494,"width":90,"height":50,"text":"60","color":"#09bf11"},{"element_id":1273,"x":633.99666195192,"y":981.02759684494,"width":90,"height":50,"text":"61","color":"#09bf11"},{"element_id":1274,"x":728.6296749785,"y":981.02759684494,"width":90,"height":50,"text":"62","color":"#09bf11"},{"element_id":1275,"x":824.11954728441,"y":981.02759684494,"width":90,"height":50,"text":"63","color":"#09bf11"},{"element_id":1276,"x":918.08832969012,"y":981.02759684494,"width":90,"height":50,"text":"64","color":"#09bf11"},{"element_id":1277,"x":1029.5396762643,"y":981.02759684494,"width":90,"height":50,"text":"65","color":"#09bf11"},{"element_id":1278,"x":1132.2497407543,"y":981.02759684494,"width":90,"height":50,"text":"66","color":"#09bf11"},{"element_id":1279,"x":1226.21852316,"y":981.02759684494,"width":90,"height":50,"text":"67","color":"#09bf11"},{"element_id":1280,"x":540.02787954621,"y":1035.6602602363,"width":90,"height":50,"text":"68","color":"#09bf11"},{"element_id":1281,"x":633.99666195192,"y":1035.6602602363,"width":90,"height":50,"text":"69","color":"#09bf11"},{"element_id":1282,"x":728.78576294994,"y":1035.6602602363,"width":90,"height":50,"text":"70","color":"#09bf11"},{"element_id":1283,"x":824.11954728441,"y":1035.6602602363,"width":90,"height":50,"text":"71","color":"#09bf11"},{"element_id":1284,"x":917.1617270848,"y":1035.6602602363,"width":90,"height":50,"text":"72","color":"#09bf11"},{"element_id":1285,"x":1132.2497407543,"y":1035.6602602363,"width":90,"height":50,"text":"73","color":"#09bf11"},{"element_id":1286,"x":1226.21852316,"y":1035.6602602363,"width":90,"height":50,"text":"74","color":"#09bf11"},{"element_id":1287,"x":500.69237743297,"y":1232.337848445,"width":90,"height":50,"text":"75","color":"#09bf11"},{"element_id":1288,"x":594.04736424682,"y":1232.337848445,"width":90,"height":50,"text":"76","color":"#09bf11"},{"element_id":1289,"x":688.6299422444,"y":1232.337848445,"width":90,"height":50,"text":"77","color":"#09bf11"},{"element_id":1290,"x":785.02686846114,"y":1232.337848445,"width":90,"height":50,"text":"78","color":"#09bf11"},{"element_id":1291,"x":881.42379467788,"y":1232.337848445,"width":90,"height":50,"text":"79","color":"#09bf11"},{"element_id":1292,"x":976.89411828931,"y":1232.337848445,"width":90,"height":50,"text":"80","color":"#09bf11"},{"element_id":1293,"x":1071.7895033003,"y":1232.337848445,"width":90,"height":50,"text":"81","color":"#09bf11"},{"element_id":1294,"x":492.83228351189,"y":1389.66702228,"width":50,"height":90,"text":"82","color":"#09bf11"},{"element_id":1295,"x":548.76916163046,"y":1380.9386928694,"width":90,"height":50,"text":"83","color":"#09bf11"},{"element_id":1296,"x":642.73794403617,"y":1380.9386928694,"width":90,"height":50,"text":"84","color":"#09bf11"},{"element_id":1297,"x":736.70672644188,"y":1380.9386928694,"width":90,"height":50,"text":"85","color":"#09bf11"},{"element_id":1298,"x":830.6755088476,"y":1380.9386928694,"width":90,"height":50,"text":"86","color":"#09bf11"},{"element_id":1299,"x":925.30852187417,"y":1380.9386928694,"width":90,"height":50,"text":"87","color":"#09bf11"},{"element_id":1300,"x":1029.5396762643,"y":1380.9386928694,"width":90,"height":50,"text":"88","color":"#09bf11"},{"element_id":1301,"x":548.76916163046,"y":1435.5713562607,"width":90,"height":50,"text":"89","color":"#09bf11"},{"element_id":1302,"x":642.73794403617,"y":1435.5713562607,"width":90,"height":50,"text":"90","color":"#09bf11"},{"element_id":1303,"x":736.70672644189,"y":1435.5713562607,"width":90,"height":50,"text":"91","color":"#09bf11"},{"element_id":1304,"x":830.6755088476,"y":1435.5713562607,"width":90,"height":50,"text":"92","color":"#09bf11"},{"element_id":1305,"x":924.64429125331,"y":1435.5713562607,"width":90,"height":50,"text":"93","color":"#09bf11"},{"element_id":1306,"x":490.24545246923,"y":1550.9058198654,"width":90,"height":50,"text":"94","color":"#09bf11"},{"element_id":1307,"x":584.94306109937,"y":1550.9058198654,"width":90,"height":50,"text":"95","color":"#09bf11"},{"element_id":1308,"x":679.64066972951,"y":1550.9058198654,"width":90,"height":50,"text":"96","color":"#09bf11"},{"element_id":1309,"x":774.33827835965,"y":1550.9058198654,"width":90,"height":50,"text":"97","color":"#09bf11"},{"element_id":1310,"x":869.03588698979,"y":1550.9058198654,"width":90,"height":50,"text":"98","color":"#09bf11"},{"element_id":1311,"x":963.89217461185,"y":1550.9058198654,"width":90,"height":50,"text":"99","color":"#09bf11"},{"element_id":1312,"x":1057.1526594626,"y":1550.9058198654,"width":90,"height":50,"text":"100","color":"#09bf11"},{"element_id":1313,"x":490.24545246923,"y":1606.7527848026,"width":90,"height":50,"text":"101","color":"#09bf11"},{"element_id":1314,"x":584.94306109937,"y":1606.7527848026,"width":90,"height":50,"text":"102","color":"#09bf11"},{"element_id":1315,"x":679.64066972951,"y":1606.7527848026,"width":90,"height":50,"text":"103","color":"#09bf11"},{"element_id":1316,"x":774.33827835965,"y":1606.7527848026,"width":90,"height":50,"text":"104","color":"#09bf11"},{"element_id":1317,"x":869.61073650156,"y":1606.7527848026,"width":90,"height":50,"text":"105","color":"#09bf11"},{"element_id":1318,"x":962.74247558831,"y":1606.7527848026,"width":90,"height":50,"text":"106","color":"#09bf11"},{"element_id":1319,"x":1058.4311042501,"y":1606.7527848026,"width":90,"height":50,"text":"107","color":"#09bf11"},{"element_id":1320,"x":355.41909807513,"y":1251.0960264617,"width":50,"height":90,"text":"108","color":"#09bf11"},{"element_id":1321,"x":355.41909807513,"y":1345.793053964,"width":50,"height":90,"text":"109","color":"#09bf11"},{"element_id":1322,"x":355.41909807513,"y":1469.62762839,"width":50,"height":90,"text":"110","color":"#09bf11"},{"element_id":1323,"x":355.41909807513,"y":1564.3246558923,"width":50,"height":90,"text":"111","color":"#09bf11"},{"element_id":1324,"x":355.41909807513,"y":1659.0216833946,"width":50,"height":90,"text":"112","color":"#09bf11"},{"element_id":1325,"x":520.09762287219,"y":1734.9583516452,"width":90,"height":50,"text":"113","color":"#09bf11"},{"element_id":1326,"x":616.51409778342,"y":1734.9583516452,"width":90,"height":50,"text":"114","color":"#09bf11"},{"element_id":1327,"x":710.48288018913,"y":1734.9583516452,"width":90,"height":50,"text":"115","color":"#09bf11"},{"element_id":1328,"x":942.12685542181,"y":1732.7730451095,"width":90,"height":50,"text":"116","color":"#09bf11"},{"element_id":1329,"x":1036.0956378275,"y":1732.7730451095,"width":90,"height":50,"text":"117","color":"#09bf11"},{"element_id":1330,"x":1130.0644202332,"y":1732.7730451095,"width":90,"height":50,"text":"118","color":"#09bf11"},{"element_id":1331,"x":1224.033202639,"y":1732.7730451095,"width":90,"height":50,"text":"119","color":"#09bf11"},{"element_id":1332,"x":1318.0019850447,"y":1732.7730451095,"width":90,"height":50,"text":"120","color":"#09bf11"},{"element_id":1333,"x":1411.9707674504,"y":1732.7730451095,"width":90,"height":50,"text":"121","color":"#09bf11"},{"element_id":1334,"x":1505.9395498561,"y":1732.7730451095,"width":90,"height":50,"text":"122","color":"#09bf11"},{"element_id":1335,"x":1598.9817296565,"y":1732.7730451095,"width":90,"height":50,"text":"123","color":"#09bf11"},{"element_id":1336,"x":1693.8771146675,"y":1732.7730451095,"width":90,"height":50,"text":"124","color":"#09bf11"},{"element_id":1337,"x":1679.2441016409,"y":1535.4309745425,"width":50,"height":90,"text":"125","color":"#09bf11"},{"element_id":1338,"x":1679.2441016409,"y":1441.4627935095,"width":50,"height":90,"text":"126","color":"#09bf11"},{"element_id":1339,"x":1679.2441016409,"y":1347.4946124764,"width":50,"height":90,"text":"127","color":"#09bf11"},{"element_id":1340,"x":1678.9817296565,"y":1253.5264314433,"width":50,"height":90,"text":"128","color":"#09bf11"},{"element_id":1341,"x":1510.9744215191,"y":1249.155818372,"width":50,"height":90,"text":"129","color":"#09bf11"},{"element_id":1342,"x":1510.9744215191,"y":1343.1239994051,"width":50,"height":90,"text":"130","color":"#09bf11"},{"element_id":1343,"x":1510.9744215191,"y":1467.6864719373,"width":50,"height":90,"text":"131","color":"#09bf11"},{"element_id":1344,"x":1461.5029768809,"y":1563.0464644169,"width":90,"height":50,"text":"132","color":"#09bf11"},{"element_id":1345,"x":1455.3682444714,"y":1467.1994994797,"width":50,"height":90,"text":"133","color":"#09bf11"},{"element_id":1346,"x":1455.3682444714,"y":1343.3649250537,"width":50,"height":90,"text":"134","color":"#09bf11"},{"element_id":1347,"x":1455.3682444714,"y":1248.6678975514,"width":50,"height":90,"text":"135","color":"#09bf11"},{"element_id":1348,"x":1331.5329101089,"y":1248.6678975514,"width":50,"height":90,"text":"136","color":"#09bf11"},{"element_id":1349,"x":1331.5329101089,"y":1343.3649250537,"width":50,"height":90,"text":"137","color":"#09bf11"},{"element_id":1350,"x":1330.8293148331,"y":1438.0619525559,"width":50,"height":90,"text":"138","color":"#09bf11"},{"element_id":1351,"x":1329.9670405655,"y":1532.7589800582,"width":50,"height":90,"text":"139","color":"#09bf11"},{"element_id":1352,"x":1275.6856024552,"y":1532.7589800582,"width":50,"height":90,"text":"140","color":"#09bf11"},{"element_id":1353,"x":1275.6856024552,"y":1438.0619525559,"width":50,"height":90,"text":"141","color":"#09bf11"},{"element_id":1354,"x":1276.161639431,"y":1343.3649250537,"width":50,"height":90,"text":"142","color":"#09bf11"},{"element_id":1355,"x":1275.6856024552,"y":1248.6678975514,"width":50,"height":90,"text":"143","color":"#09bf11"},{"element_id":1356,"x":1281.8203348648,"y":1193.9708700491,"width":90,"height":50,"text":"144","color":"#09bf11"},{"element_id":1357,"x":1308.5299167861,"y":856.46095151535,"width":90,"height":50,"text":"145","color":"#09bf11"},{"element_id":1358,"x":1300.2098638554,"y":760.61398657811,"width":50,"height":90,"text":"146","color":"#09bf11"},{"element_id":1359,"x":1300.2098638554,"y":665.91695907583,"width":50,"height":90,"text":"147","color":"#09bf11"},{"element_id":1360,"x":1301.4041643449,"y":571.21993157356,"width":50,"height":90,"text":"148","color":"#09bf11"},{"element_id":1361,"x":1301.0721381231,"y":476.52290407128,"width":50,"height":90,"text":"149","color":"#09bf11"},{"element_id":1362,"x":1357.9996687403,"y":476.52290407128,"width":50,"height":90,"text":"150","color":"#09bf11"},{"element_id":1363,"x":1356.101772975,"y":571.21993157356,"width":50,"height":90,"text":"151","color":"#09bf11"},{"element_id":1364,"x":1357.9996687403,"y":665.91695907583,"width":50,"height":90,"text":"152","color":"#09bf11"},{"element_id":1365,"x":1357.9996687403,"y":760.61398657811,"width":50,"height":90,"text":"153","color":"#09bf11"},{"element_id":1366,"x":1535.4969902353,"y":379.39774765869,"width":50,"height":90,"text":"154","color":"#09bf11"},{"element_id":1367,"x":1535.9430939831,"y":474.09477516097,"width":50,"height":90,"text":"155","color":"#09bf11"},{"element_id":1368,"x":1535.4969902353,"y":568.79180266324,"width":50,"height":90,"text":"156","color":"#09bf11"},{"element_id":1369,"x":1535.4969902353,"y":663.48883016552,"width":50,"height":90,"text":"157","color":"#09bf11"},{"element_id":1370,"x":1535.4969902353,"y":758.1858576678,"width":50,"height":90,"text":"158","color":"#09bf11"},{"element_id":1371,"x":1537.2215387706,"y":852.88288517007,"width":50,"height":90,"text":"159","color":"#09bf11"}]}}',
            'layout_elements' => json_encode([]),
            'status' => '2',
            'published_at' => now(),
        ]);

       // Layout aus der Datenbank laden
        $layout = json_decode($retailSpace->layout, true);
        $shelves = $layout['elements']['shelves'] ?? [];

        if (!empty($shelves)) {
            // Für jedes Regal im Layout erstellen wir einen Shelf-Eintrag
            foreach ($shelves as &$shelfData) {  // Hier das &$ verwenden, um das Layout zu referenzieren
                // Regal erstellen oder aktualisieren
                $shelve = Shelve::updateOrCreate([
                    'id' => $shelfData['element_id'],
                    'retail_space_id' => $retailSpace->id,
                    'shelve_type_id' => 1, // Beispielwert
                    'floor_number' => $shelfData['text'],
                    'shelve_id' => $shelfData['element_id'],
                    'position_x' => $shelfData['x'],
                    'position_y' => $shelfData['y'],
                ]);

                // Die `element_id` im Layout mit der tatsächlichen ID des neu erzeugten Regals aktualisieren
                $shelfData['element_id'] = $shelve->id;
                $layout['elements']['shelves'] = $shelves;
                // Layout nach der Bearbeitung zurück in JSON umwandeln und speichern
                $retailSpace->layout = json_encode($layout);
    
                // Sicherstellen, dass das Layout nach der Änderung gespeichert wird
                $retailSpace->save();
            }

        }

        
        // Feiertage in Hamburg
        $holidays = [
            // Feiertage 2025
            ['date' => '2025-01-01', 'name' => 'Neujahr'],
            ['date' => '2025-04-18', 'name' => 'Karfreitag'],
            ['date' => '2025-04-21', 'name' => 'Ostermontag'],
            ['date' => '2025-05-01', 'name' => 'Tag der Arbeit'],
            ['date' => '2025-05-29', 'name' => 'Himmelfahrt'],
            ['date' => '2025-06-09', 'name' => 'Pfingstmontag'],
            ['date' => '2025-10-03', 'name' => 'Tag der Deutschen Einheit'],
            ['date' => '2025-12-25', 'name' => '1. Weihnachtsfeiertag'],
            ['date' => '2025-12-26', 'name' => '2. Weihnachtsfeiertag'],

            // Feiertage 2026
            ['date' => '2026-01-01', 'name' => 'Neujahr'],
            ['date' => '2026-04-03', 'name' => 'Karfreitag'],
            ['date' => '2026-04-06', 'name' => 'Ostermontag'],
            ['date' => '2026-05-01', 'name' => 'Tag der Arbeit'],
            ['date' => '2026-05-14', 'name' => 'Himmelfahrt'],
            ['date' => '2026-05-25', 'name' => 'Pfingstmontag'],
            ['date' => '2026-10-03', 'name' => 'Tag der Deutschen Einheit'],
            ['date' => '2026-12-25', 'name' => '1. Weihnachtsfeiertag'],
            ['date' => '2026-12-26', 'name' => '2. Weihnachtsfeiertag'],
        ];

        // Gesperrte Tage (Wochentage, 0 = Sonntag, 6 = Samstag)
        $disabledDays = [
            '0' => 'Sonntag',
        ];

        // Feiertage eintragen
        foreach ($holidays as $holiday) {
            Setting::updateOrCreate(
                ['key' => 'holiday_' . strtolower(str_replace(' ', '_', $holiday['name']))],
                [
                    'value' => $holiday['date'],
                    'type' => 'holiday',
                    'key' => $holiday['name'],
                ]
            );
        }

        // Gesperrte Wochentage eintragen
        foreach ($disabledDays as $dayKey => $dayName) {
            Setting::updateOrCreate(
                ['key' => 'disabled_day_' . strtolower($dayName)],
                [
                    'value' => $dayKey,
                    'type' => 'disabled_day',
                    'key' => $dayName,
                ]
            );
        }

        // Bonus für den 25. Januar mit 20% Rabatt
        Bonus::create([
            'name' => 'Frühbucher Rabatt 20%',
            'description' => '25% Rabatt, wenn der User für den 25. Januar bucht.',
            'type' => 'percentage', // Rabatttyp (Prozentsatz)
            'value' => 25.00, // 20% Rabatt
            'criteria' => json_encode([
                'start_date' => '2024-01-25', // Das Datum, für das der Rabatt gilt
            ]),
            'validity_period' => null, // Gültigkeitsdauer (optional)
            'user_id' => null, // Wenn du einen Benutzer für den Bonus festlegen möchtest, hier die User-ID setzen
            'status' => 1, // Aktiver Bonus
            'is_redeemable' => true, // Bonus ist einlösbar
        ]);

        $adminMails = [
            'new_booking' => true,
            'new_user' => true,
            'user_payout' => true,
            'sale_notification' => true,
        ];
        
        $userMails = [
            'booking_confirmation' => true,
            'sale_notification' => true,
            'reminder_start_3days' => true,
            'reminder_start_tomorrow' => true,
            'reminder_end_tomorrow' => true,
        ];
        
        foreach ($adminMails as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'mails'],
                ['value' => json_encode($value)]
            );
        }
        
        foreach ($userMails as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'mails'],
                ['value' => json_encode($value)]
            );
        }
        
                // API-Einstellungen
                $apiSettings = [
                    'paypal_api_client_id' => [
                        'value' => 'AZW-oHB2bK_h2aahITcmDKfsmbyGr4IU-Ed0xX-XPch-07qDeYV-Y3Ip80dNvAIGZwIGwOK2NaIdzN4b', 
                    ],
                    'paypal_api' => [
                        'value' => 'ENbHxqR8Li6TCFGCO9Ml-mW63UDYz9hTHgpeNKw3Y9yNX3qhljnnhuur7DzkJJ6lWciKgzUp61104ZwK',
                    ],
                    'cash_register_api_url' => [
                        'value' => '"\"https:\/\/api.flour.cloud\/api\/v2\/\""',
                    ],
                    'cash_register_api_key' => [
                        'value' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJrZXlVc2VyIjoiNjc0YjIxNjljODhlNDgwNjc3MmMxY2QyIiwia2V5Q2xpZW50IjoiNjc0YjIxNjljODhlNDgwNjc3MmMxY2NkIiwidHlwZSI6ImJhc2ljdjEiLCJpYXQiOjE3MzYzMzI4NzMsImV4cCI6MTg5NDEyMDg3M30.nN40DF2DyJuvP8iiWv0pn5wOByjmCR_HZN86bUyzvhM', 
                    ],
                ];
        
                // API-Einstellungen speichern
                foreach ($apiSettings as $key => $setting) {
                    Setting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => json_encode($setting['value']),
                            'type' => 'api',
                        ]
                    );
                }


        // Perioden eintragen (7, 14, 21 Tage)
        $periods = [
            '7' => [
                'duration' => 7,
                'price' => 26,  // Beispielpreis
                'description' => 'Ideal, um einen kurzen Test durchzuführen.',
            ],
            '14' => [
                'duration' => 14,
                'price' => 46,  // Beispielpreis
                'description' => 'Optimal, um Ihren Verkaufsbereich zu testen.',
            ],
            '21' => [
                'duration' => 21,
                'price' => 66,  // Beispielpreis
                'description' => 'Perfekt für ein langfristiges Angebot.',
            ],
        ];

       // Für jede Periode einen eigenen Setting-Eintrag erstellen
        foreach ($periods as $period) {
            Setting::updateOrCreate(
                ['key' => 'period_' . $period['duration']], // Der Schlüssel basiert auf der Dauer der Periode
                [
                    'value' => json_encode([
                        'duration' => $period['duration'],
                        'price' => $period['price'],
                        'description' => $period['description'],
                        'is_active' => true,
                    ]), // Die Periodendaten werden als JSON gespeichert
                    'type' => 'period', // Typ bleibt 'period'
                    'key' => "Periode: {$period['description']}",
                ]
            );
        }

        // Optimierungs-Checkboxen für Kalender und Regalauswahl
        $bookingOptimizations = [
            'optimize_calendar' => [
                'value' => false,  // Setze hier den Standardwert (true/false)
                'description' => 'Kalenderauswahl optimieren',
            ],
            'optimize_shelf_selection' => [
                'value' => false,  // Setze hier den Standardwert (true/false)
                'description' => 'Regalauswahl optimieren',
            ]
        ];

        // Optimierungen speichern
        foreach ($bookingOptimizations as $key => $setting) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $setting['value'],
                    'type' => 'booking_optimization',
                    'key' => $setting['description'],
                ]
            );
        }


      



        // Beispielinhalte für verschiedene Seiten
        $contents = [
            // Hero Section
            ['key' => 'hero_section_title', 'value' => 'Willkommen bei MiniFinds', 'type' => 'text'],
            ['key' => 'hero_section_subtitle', 'value' => 'Der Markt für Second-Hand-Kinderschätze', 'type' => 'text'],
            ['key' => 'hero_section_button_text', 'value' => 'Stand buchen', 'type' => 'text'],
            ['key' => 'hero_section_button_link', 'value' => '/booking', 'type' => 'text'],
            ['key' => 'hero_section_background_image', 'value' => 'site-images/background.webp', 'type' => 'text'],

            // Features Section
            ['key' => 'features_section_title', 'value' => 'Warum MiniFinds?', 'type' => 'text'],
            ['key' => 'features_section_subtitle', 'value' => 'Entdecke die Vorteile unseres Marktplatzes', 'type' => 'text'],

            ['key' => 'feature_1_title', 'value' => 'Bequem und stressfrei', 'type' => 'text'],
            ['key' => 'feature_1_description', 'value' => 'Bring deine Artikel vorbei und richte dein Regal ein. Um den Rest kümmern wir uns.', 'type' => 'text'],
            ['key' => 'feature_1_image', 'value' => 'site-images/1.jpg', 'type' => 'text'],

            ['key' => 'feature_2_title', 'value' => 'Nachhaltig', 'type' => 'text'],
            ['key' => 'feature_2_description', 'value' => 'Gib gut erhaltener Kinderkleidung und Spielzeug eine neue Chance.', 'type' => 'text'],
            ['key' => 'feature_2_image', 'value' => 'site-images/3.jpg', 'type' => 'text'],

            ['key' => 'feature_3_title', 'value' => 'Transparent', 'type' => 'text'],
            ['key' => 'feature_3_description', 'value' => 'Behalte jederzeit den Überblick über deine Verkäufe und Einnahmen.', 'type' => 'text'],
            ['key' => 'feature_3_image', 'value' => 'site-images/2.jpg', 'type' => 'text'],

            // Products Section
            ['key' => 'products_section_title', 'value' => 'Entdecke unsere beliebtesten Produkte', 'type' => 'text'],
            ['key' => 'products_section_subtitle', 'value' => 'Stöbere durch unser vielfältiges Angebot an Kinderschätzen', 'type' => 'text'],

              // FAQ Section
            ['key' => 'Was kann ich bei MiniFinds verkaufen?', 'value' => 'Bei MiniFinds kannst du alles rund ums Kind verkaufen. Angefangen bei Kinderkleidung, Spielzeugen und Büchern, kannst du ebenso größere Produkte wie Kinderwägen und -sitze, Laufräder verkaufen. Solltest du größere Produkte anbieten wollen, dann ruf uns vorher bitte einmal an, damit wir klären können, ob Platz dafür vorhanden ist.', 'type' => 'faq'],
            ['key' => 'Welchen Regaltyp gibt es bei MiniFinds?', 'value' => 'Das Regal bei MiniFinds besteht aus 2 Kleiderstangen sowie 2 Regalböden auf denen du deine Artikel auslegen kannst. ', 'type' => 'faq'],
            ['key' => 'Wie lang kann ich ein Regal anmieten?', 'value' => 'Du kannst das Regal für 7, 14 oder 21 Werktage mieten. ', 'type' => 'faq'],
            ['key' => 'Kann ich meine Mietzeit verlängern?', 'value' => 'Die Verlängerung deiner Mietzeit ist leider nicht möglich. Sofern freie Regale vorhanden sind, kannst du aber natürlich ein neues Regal anmieten.', 'type' => 'faq'],
            ['key' => 'Kann ich mehrere Stände gleichzeitig anmieten?', 'value' => 'Klar, kannst du mehrere Stände gleichzeitig anmieten. Beim Aushängen deiner Ware solltest du nur darauf achten, dass du deine Produkte dem richtigen Regal zuordnest.', 'type' => 'faq'],
            ['key' => 'Wann kann ich meinen Stand einräumen?', 'value' => 'Dein Regal kannst innerhalb unserer Öffnungszeiten von 9- 18 Uhr pünktlich zum Start deiner Miete einräumen oder aber einen Tag davor 1 Stunde vor Ladenschluss- Mo-Fr ab 17 Uhr, samstags ab 15 Uhr. ', 'type' => 'faq'],
            ['key' => 'Wie viele Produkte kann ich in mein Regal hängen?', 'value' => 'Es ist dir überlassen, wie viele Produkte du in dein Regal hängst. Wir empfehlen dir jedoch, dein Regal nicht zu voll zu hängen. Ein überfülltes Regal schreckt die Käufer meist eher ab und weckt weniger Interesse zu stöbern.', 'type' => 'faq'],
            ['key' => 'Kann ich Produkte nachlegen?', 'value' => 'Ja, du kannst jederzeit weitere Produkte hinzufügen und in dein Regal hängen. Die Etiketten bekommst du bei uns an der Kasse.', 'type' => 'faq'],
            ['key' => 'Wer bestimmt die Preise für meine Produkte?', 'value' => 'Den Preis für deine angebotenen Produkte legst du selbst fest.', 'type' => 'faq'],
            ['key' => 'Wie beschreibe ich meine Produkte?', 'value' => 'Damit sich deine Produkte erfolgreich verkaufen und auch du beim Aufhängen weißt, welches Produkt gemeint ist, solltest du sie gut beschreiben: Marke/Hersteller, Produkttyp (Kleid, Schuhe, Hose, Größe). Auch die Farbe oder andere Merkmale an deinen Produkten können benannt werden.', 'type' => 'faq'],
            ['key' => 'Wann wird mir mein Umsatz ausgezahlt?', 'value' => 'Einen Tag nach Mietende erscheint in deiner Standübersicht ein Auszahlungsknopf.', 'type' => 'faq'],
        ];

        foreach ($contents as $content) {
            WebContent::updateOrCreate(['key' => $content['key']], $content);
        }


    }


    

}
