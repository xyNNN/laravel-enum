<?php

namespace Spatie\Enum\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRequestTransformMacro();
    }

    public function registerRequestTransformMacro()
    {
        if (! Request::hasMacro('transformEnums')) {
            Request::macro('transformEnums', function ($rules = []) {
                /** @var Request $request */
                $request = $this;

                foreach ($rules as $key => $enumClass) {
                    if (empty($request[$key])) {
                        continue;
                    }

                    $request[$key] = forward_static_call(
                        $enumClass.'::make',
                        $request[$key]
                    );
                }

                return $this;
            });
        }
    }
}
