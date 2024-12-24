    <?php

    namespace App\Http\Controllers\Create;

    use App\Models\Post;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use App\Http\Controllers\Controller;

    class SingleInsertController extends Controller
    {
        public function insertWithEloquent(Request $request)
        {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]);

            $post = Post::create([
                'title' => $validatedData['title'],
                'author_id' => $validatedData['author_id'],
                'slug' => $validatedData['slug'],
                'body' => $validatedData['body'],
            ]);

            return response()->json([
                'message' => 'Post created successfully with Eloquent',
                'post' => $post
            ], 201);
        }

        public function insertWithQueryBuilder(Request $request)
        {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]);

            DB::table('posts')->insert([
                'title' => $validatedData['title'],
                'author_id' => $validatedData['author_id'],
                'slug' => $validatedData['slug'],
                'body' => $validatedData['body'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Post created successfully with Query Builder',
                'post' => $validatedData
            ], 201);
        }

        public function insertWithRawSQL(Request $request)
        {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]);

            DB::insert('INSERT INTO posts (title, author_id, slug, body, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())', [
                $validatedData['title'],
                $validatedData['author_id'],
                $validatedData['slug'],
                $validatedData['body'],
            ]);

            return response()->json([
                'message' => 'Post created successfully with Raw SQL',
                'post' => $validatedData
            ], 201);
        }
    }
