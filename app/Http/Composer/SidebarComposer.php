<?php

namespace App\Http\Composer;

use App\Models\Core\Builder\Table\CustomTable;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $table = CustomTable::all();

        $menu = [
            [
                'icon' => 'upload',
                'name' => 'Update Reaction',
                'url' => request()->root() . '/reaction',
                'permission' => true
            ],
            [
                'icon' => 'info',
                'name' => "Scan Info",
                'url' => request()->root() . '/scan-info',
                'permission' => "true",
            ],
            [
                'icon' => 'codepen',
                'name' => "Scan Groups",
                'url' => request()->root() . '/scan-group',
                'permission' => "true",
            ],
            [
                'icon' => 'credit-card',
                'name' => "Scan Page",
                'url' => request()->root() . '/scan-page',
                'permission' => "true",
            ],
            [
                'icon' => 'power',
                'name' => 'TNS Ngày',
                'url' => request()->root() . '/tns/day-type',
                'permission' => "true",
//                'subMenu' => [
//                    [
//                        'name' => 'Báo cáo ngày',
//                        'url' => request()->root() . '/tns/day-type',
//                        'permission' => "true"
//                    ],
//                    [
//                        'name' => 'Báo cáo tuần',
//                        'url' => request()->root() . '/tns/week-type',
//                        'permission' => "true"
//                    ],
//                ],
            ],
            [
                'icon' => 'power',
                'name' => 'TNS Tuần/Tháng',
                'url' => request()->root() . '/tns/week-type',
                'permission' => "true",
//                'subMenu' => [
//                    [
//                        'name' => 'Báo cáo ngày',
//                        'url' => request()->root() . '/tns/day-type',
//                        'permission' => "true"
//                    ],
//                    [
//                        'name' => 'Báo cáo tuần',
//                        'url' => request()->root() . '/tns/week-type',
//                        'permission' => "true"
//                    ],
//                ],
            ],
            [
                'icon' => 'user-check',
                'name' => trans('custom.user_and_roles'),
                'url' => request()->root() . '/users-and-roles',
                'permission' => true,
            ],
        ];


        $view->with(['data' => $menu]);
    }
}
