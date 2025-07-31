<?PHP

// app/Http/Controllers/WilayahController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function jumlahWilayah()
    {
        $jumlah = Wilayah::count();

        return response()->json([
            'jumlah' => $jumlah
        ]);
    }
}
