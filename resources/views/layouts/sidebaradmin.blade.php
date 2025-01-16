<div class="sidebar">
    <div class="system-title">&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</div>

    <div class="toolbar mt-10">
      <ul class="toolbar-menu">
        <li class="toolbar-menu-item">
          <a href="{{route('company.create')}}" class="toolbar-menu-link">Add Company</a>
        </li>
        <li class="toolbar-menu-item">
            <a href="{{route('company.index')}}" class="toolbar-menu-link">List Of Company</a>
          </li>
          <li class=" logout-item">
            <a class="toolbar-menu-link">
            <form action="{{ route('logout') }}" method="POST" >
                @csrf
                <button type="submit" >Logout</button>
                </form>
            </a>
          </li>
      </ul>
    </div>
  </div>
