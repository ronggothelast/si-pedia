<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $lecturers = Lecturer::when($request->q, fn ($query, $q) =>
            $query->where('nidn', 'like', "%{$q}%")
                  ->orWhere('username', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%"))
            ->paginate(5);

        return view('pages.dosen_index', compact('lecturers'));
    }

    public function create()
    {
        return view('pages.dosen_create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nidn'     => 'required|string|max:50',
            'username' => 'required|string|max:100',
            'address'  => 'required|string|max:255',
            'photo'    => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('lecturers', 'public');
        }

        $lecturer = Lecturer::create($data);

        $this->logActivity('create', "Created lecturer: {$lecturer->username}", $lecturer);

        return redirect()->route('admin.dosen.index')->with('success', 'Lecturer added successfully.');
    }

    public function edit(Lecturer $lecturer)
    {
        return view('pages.dosen_create', compact('lecturer'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $data = $request->validate([
            'nidn'     => 'required|string|max:50',
            'username' => 'required|string|max:100',
            'address'  => 'required|string|max:255',
            'photo'    => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('lecturers', 'public');
        }

        $lecturer->update($data);

        $this->logActivity('update', "Updated lecturer: {$lecturer->username}", $lecturer);

        return redirect()->route('admin.dosen.index')->with('success', 'Lecturer updated successfully.');
    }

    public function approve(Lecturer $lecturer)
    {
        $lecturer->update(['status' => 'active']);

        $this->logActivity('approve', "Approved lecturer: {$lecturer->username}", $lecturer);

        return back()->with('success', 'Lecturer approved successfully.');
    }

    public function acc(Lecturer $lecturer)
    {
        return view('pages.dosen_acc', compact('lecturer'));
    }

    public function destroy(Lecturer $lecturer)
    {
        $this->logActivity('delete', "Deleted lecturer: {$lecturer->username}", $lecturer);

        $lecturer->delete();
        return back()->with('status', 'Lecturer deleted.');
    }
}
