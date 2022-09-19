{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class='nav-item'><div class="nav-item-divider"></div></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('leagues') }}"><i class="nav-icon la la-futbol"></i> Leagues</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('tournaments') }}"><i class="nav-icon la la-futbol"></i> Tournaments</a></li>
<li class='nav-item'><div class="nav-item-divider"></div></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('teams') }}"><i class="nav-icon la la-futbol"></i> Teams</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('groups') }}"><i class="nav-icon la la-futbol"></i> Groups</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('stages') }}"><i class="nav-icon la la-futbol"></i> Stages</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('games') }}"><i class="nav-icon la la-futbol"></i> Games</a></li>

<li class='nav-item'><div class="nav-item-divider"></div></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('users') }}"><i class="nav-icon la la-users"></i> Users</a></li>

