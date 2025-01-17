<div class="sidebar">
    <div class="system-title">&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</div>
    <div class="nav-container" style="margin-top:50px ;">
    <div class="nav-item">
      <h3 class="nav-title"><a href="{{route('branches.create')}}">Add Branch</a></h3>
    </div>

    <div class="nav-item" >
      <h3 class="nav-title"> <a href="{{route('categories.create')}}">Categorie</a></h3>
    </div>

    <div class="nav-item" >
      <h3 class="nav-title"><a href="{{route('users.create')}}">Users</a></h3>
    </div>

    <div class="nav-item" onclick="showSection(&apos;employees-container&apos;)">
      <h3 class="nav-title">&#x642;&#x627;&#x626;&#x645;&#x629; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h3>
    </div>
    <div class="nav-item" onclick="document.getElementById('logout-form').submit();">
      <h3 class="nav-title"> <a>
        <a href="javascript:void(0);">
            Logout
        </a>
    </h3>
    </div>
  </div>

  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
