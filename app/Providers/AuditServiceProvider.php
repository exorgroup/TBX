namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\AuditServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the AuditServiceProvider manually
        $auditProvider = new AuditServiceProvider($this->app);
        $auditProvider->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Boot the AuditServiceProvider manually
        $auditProvider = new AuditServiceProvider($this->app);
        $auditProvider->boot();
    }
}