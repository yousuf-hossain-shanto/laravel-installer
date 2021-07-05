<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        set_time_limit(300);
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function database(Request $request)
    {
        $response = $this->databaseManager->migrateAndSeed();
        try {
            if ($request->email && $request->password) {
                Staff::query()->create([
                    'role_id' => null,
                    'name' => 'Super Admin',
                    'email' => $request->email,
                    'phone' => null,
                    'password' => Hash::make($request->password)
                ]);
            }
        } catch (\Exception $exception) {}
        return redirect()->route('LaravelInstaller::final')
                         ->with(['message' => $response]);
    }
}
