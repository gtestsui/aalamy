<?php

namespace Modules\HelpCenter\Traits\ModelRelations;


trait HelpCenterCategoryRelations
{

    //Relations
    public function UserGuides(){
        return $this->hasMany('Modules\HelpCenter\Models\HelpCenterUserGuide','category_id');
    }

}
