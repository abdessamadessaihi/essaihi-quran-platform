<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AdminUserController extends Controller
{
    public function index()    { return view('coming-soon'); }
    public function show($id)  { return view('coming-soon'); }
    public function edit($id)  { return view('coming-soon'); }
    public function update($id){ return back(); }
    public function destroy($id){ return back(); }
}