<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class db_controller extends Controller
{
function index(){
$data = DB::table('appts')->get();
return view('index', ['data'=>$data]);
}


 function destroy($id) {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('index');
    }
}
