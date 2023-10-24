<?php

namespace App\Console\Commands;

use App\Models\Email;
use App\Models\User;
use App\Utils\FirstProvider;
use App\Utils\SecondProvider;
use App\Utils\ThirdProvider;
use Exception;
use Illuminate\Console\Command;

class Search extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search {name} {company} {linkedin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search in API by name, company and/or linkedin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::firstOrCreate([
            'name' => $this->argument('name'),
            'company' => $this->argument('company'),
            'linkedin' => $this->argument('linkedin'),
        ]);

        if ($user->getProviders()) {
            foreach ($user->getProviders() as $provider) {
                switch ($provider) {
                    case User::FIRST_PROVIDER:
                        $providerHandler = new FirstProvider($user->name, $user->company, $user->linkedin);
                        break;
                    case User::SECOND_PROVIDER:
                        $providerHandler = new SecondProvider($user->name, $user->company, $user->linkedin);
                        break;
                    case User::THIRD_PROVIDER:
                        $providerHandler = new ThirdProvider($user->name, $user->company, $user->linkedin);
                        break;
                    default:
                        throw new Exception('Invalid provider ' . $provider );
                }
                $user->update(['closing_provider' => $provider]);
                $emails = $providerHandler->makeRequest();

                if ($emails){
                    print_r("Adding emails for user: {$user->id} from provider {$provider} \n");
                    foreach ($emails as $email){
                        if (filter_var( $email, FILTER_VALIDATE_EMAIL )){
                            Email::create([
                                'user_id' => $user->id,
                                'email' => $email,
                            ]);
                            print_r("-{$email}\n");
                        } else {
                            $this->info("-{$email} - not valid!\n");
                        }

                    }
                    return 1;
                } else {
                    print_r("No emails found for user: {$user->id} from provider {$provider} \n\n");
                }
            }
        } else {
            print_r("Finished all providers.\n\n");
            return 0;
        }
    }
}
