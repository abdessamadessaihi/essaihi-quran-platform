<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class BookmarkController extends Controller
{
    public function index()   { return view('coming-soon'); }
    public function store()   { return back(); }
    public function destroy($id){ return back(); }
}