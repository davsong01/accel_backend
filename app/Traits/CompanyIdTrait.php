<?php
    namespace App\Traits;

    use App\Scopes\CompanyIdScope;
    
    trait CompanyIdTrait{
        protected static function boot(){
            parent::boot();

            static::addGlobalScope(new CompanyIdScope());
        }
    }
?>