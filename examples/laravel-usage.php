<?php

/**
 * Laravel Usage Examples
 * 
 * These examples show how to use the Trysail SDK in Laravel applications.
 */

// Example 1: Using the Facade
use Trysail\SDK\Laravel\Facades\Trysail;

// List zones
$zones = Trysail::zones()->list();

// Get specific zone
$zone = Trysail::zones()->get('zone-id-here');

// Create zone
$newZone = Trysail::zones()->create([
    'name' => 'my-zone',
    'public_key' => 'public_key_here',
]);

// Get control plane status
$status = Trysail::controlPlane()->getStatus();

// ============================================================================

// Example 2: Using Dependency Injection in Controllers
use Trysail\SDK\TrysailClient;

class ZoneController extends Controller
{
    public function __construct(
        private TrysailClient $trysail
    ) {}
    
    public function index()
    {
        $zones = $this->trysail->zones()->list();
        
        return view('zones.index', compact('zones'));
    }
    
    public function show($id)
    {
        try {
            $zone = $this->trysail->zones()->get($id);
            return view('zones.show', compact('zone'));
        } catch (\Trysail\SDK\Exceptions\TrysailException $e) {
            if ($e->isNotFound()) {
                abort(404, 'Zone not found');
            }
            throw $e;
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'public_key' => 'required|string',
            'endpoint' => 'nullable|string',
        ]);
        
        $zone = $this->trysail->zones()->create($validated);
        
        return redirect()->route('zones.show', $zone->getId())
            ->with('success', 'Zone created successfully');
    }
}

// ============================================================================

// Example 3: Using in Artisan Commands
use Illuminate\Console\Command;

class SyncZonesCommand extends Command
{
    protected $signature = 'zones:sync';
    protected $description = 'Sync zones from Trysail Control Plane';
    
    public function handle(TrysailClient $trysail)
    {
        $this->info('Syncing zones...');
        
        try {
            $result = $trysail->controlPlane()->syncZones();
            $this->info('Sync completed successfully');
            $this->table(
                ['Key', 'Value'],
                collect($result)->map(fn($value, $key) => [$key, $value])
            );
        } catch (\Trysail\SDK\Exceptions\TrysailException $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}

// ============================================================================

// Example 4: Using in Jobs
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonitorZoneStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        private string $zoneId
    ) {}
    
    public function handle(TrysailClient $trysail)
    {
        $zone = $trysail->zones()->get($this->zoneId);
        
        if (!$zone->isActive()) {
            // Send notification or take action
            \Log::warning("Zone {$zone->getName()} is not active");
        }
    }
}

// ============================================================================

// Example 5: Error Handling
use Trysail\SDK\Exceptions\TrysailException;

try {
    $zone = Trysail::zones()->get('non-existent-id');
} catch (TrysailException $e) {
    if ($e->isNotFound()) {
        // Handle 404
        return response()->json(['error' => 'Zone not found'], 404);
    } elseif ($e->isUnauthorized()) {
        // Handle 401
        return response()->json(['error' => 'Unauthorized'], 401);
    } elseif ($e->isServerError()) {
        // Handle 5xx
        \Log::error('Trysail API error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'response' => $e->getResponseData(),
        ]);
        return response()->json(['error' => 'Server error'], 500);
    }
    
    throw $e;
}