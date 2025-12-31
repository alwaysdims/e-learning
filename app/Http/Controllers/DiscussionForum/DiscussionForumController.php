<?php

namespace App\Http\Controllers\DiscussionForum;

use App\Models\User;
use App\Models\Comment;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\ForumMember;
use Illuminate\Http\Request;
use App\Models\DiscussionForum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DiscussionForumController extends Controller
{
   // DiscussionForumController.php

    public function index()
    {
        $user = Auth::user();
        $isTeacher = $user->role === 'teacher';

        $forums = DiscussionForum::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orWhere('teacher_id', $user->id)
        ->with(['subject', 'classRoom', 'teacher', 'members.user', 'comments'])
        ->latest()
        ->get();

        $classes = ClassRoom::orderBy('name')->get();

        // Tidak ada forum yang dipilih → hanya tampilkan list
        return view('discussionForums.discussion', compact('forums', 'isTeacher', 'classes'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $isTeacher = $user->role === 'teacher';

        $forum = DiscussionForum::with(['comments.user', 'members.user', 'subject', 'classRoom'])
            ->findOrFail($id);

        // Cek akses
        $isMember = $forum->members()->where('user_id', $user->id)->exists();
        $isCreator = $forum->teacher_id === $user->id;

        if (!$isMember && !$isCreator) {
            abort(403, 'Unauthorized access.');
        }

        $isAdmin = $isCreator;

        // Ambil semua forum lagi untuk sidebar
        $forums = DiscussionForum::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orWhere('teacher_id', $user->id)
        ->with(['subject', 'classRoom', 'teacher', 'members.user', 'comments'])
        ->latest()
        ->get();

        $classes = ClassRoom::orderBy('name')->get();

        return view('discussionForums.discussion', compact('forum', 'forums', 'isAdmin', 'isTeacher', 'classes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'teacher') {
            return back()->with('error', 'Only teachers can create forums.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () use ($request) {

            $teacher = Auth::user()->teacher;

            // 1️⃣ Buat forum
            $forum = DiscussionForum::create([
                'title'      => $request->title,
                'teacher_id' => $teacher->id,
                'subject_id' => $teacher->subject_id,
                'class_id'   => $request->class_id,
            ]);

            // 2️⃣ Teacher jadi admin
            ForumMember::create([
                'forum_id'  => $forum->id,
                'user_id'   => Auth::id(),
                'role'      => 'admin',
                'joined_at' => now(),
            ]);

           // 3️⃣ Ambil semua student di kelas tersebut
            $students = Student::where('class_id', $request->class_id)
            ->with('user')
            ->get();

            // 4️⃣ Masukkan semua siswa ke forum
            foreach ($students as $student) {
                ForumMember::create([
                    'forum_id'  => $forum->id,
                    'user_id'   => $student->user_id, // 🔑 dari relasi student → user
                    'role'      => 'member',
                    'joined_at' => now(),
                ]);
            }

        });

        return back()->with('success', 'Forum created and students joined automatically!');
    }


    public function update(Request $request, $id)
    {
        $forum = DiscussionForum::findOrFail($id);

        if ($forum->teacher_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to edit this forum.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $forum->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Forum updated successfully!');
    }

    public function destroy($id)
    {
        $forum = DiscussionForum::findOrFail($id);

        if ($forum->teacher_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this forum.');
        }

        $forum->delete();

        return redirect()->route('discussionForums.index')->with('success', 'Forum deleted successfully!');
    }

    public function storeComment(Request $request, $id)
    {
        $forum = DiscussionForum::findOrFail($id);

        // Cek akses
        $isMember = $forum->members()->where('user_id', Auth::id())->exists();
        $isCreator = $forum->teacher_id === Auth::id();

        if (!$isMember && !$isCreator) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'comment' => 'required|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'forum_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ];

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('forum-pictures', 'public');
        }

        Comment::create($data);

        return back()->with('success', 'Comment posted!');
    }

    public function fetchComments($id)
    {
        $forum = DiscussionForum::with([
            'comments.user'
        ])->findOrFail($id);

        return response()->json($forum->comments);
    }

}
