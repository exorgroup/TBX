{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-dropdown title="System" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-header title="Authentication" />
    <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
    <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />
     <x-backpack::menu-dropdown-header title="Support" />
     @if(backpack_user()->can('Tax_Read') || backpack_user()->hasRole('Administrator'))
        <x-backpack::menu-dropdown-item title="Tax Perm" icon="la la-percentage" :link="backpack_url('tax')" />
    @endif
    <x-backpack::menu-dropdown-item title="Tax us" icon="la la-percentage" :link="backpack_url('tax')" />
</x-backpack::menu-dropdown>



<x-backpack::menu-item title="System Audit" icon="la la-question" :link="backpack_url('system-audit')" />