use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

// Authentication routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes with Sanctum middleware (user retrieval and task management)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::resource('tasks', TaskController::class);

    // Logout route
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Token refresh route
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// Additional routes for authentication (e.g., logout, token refresh) can be added here.
