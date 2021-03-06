<div class="block-sidebar hidden-print affix-top" role="complementary" data-spy="affix" data-offset-top="170" data-offset-bottom="200">
     {{-- <div class="block-switcher">
          <div class="btn-group">
               <button type="button" class="btn btn-default btn-xs">Beginner</button>
               <button type="button" class="btn btn-default btn-xs">Intermediate</button>
               <button type="button" class="btn btn-default btn-xs">Advanced</button>
          </div>
     </div> --}}

     <div class="block-sidegroup">
          <ul class="nav block-sidenav">
               <li class="active">
                    <a href="#section-getting_started" id="sel-getting_started" data-toggle="tab">{{ trans('builder/getting_started.name') }}</a>
               </li>
               @foreach($builder->sections() as $section)
               <li>
                    <a href="#section-{{ $section }}" id="sel-{{ $section }}" data-toggle="tab">{{ trans('builder/'.$section.'.name') }}</a>
               </li>
               @endforeach
               <li>
                    <a href="#section-build" id="sel-build" data-toggle="tab">{{ trans('builder/build.name') }}</a>
               </li>
          </ul>
     </div>

     {{-- <div class="block-sharegroup">
          <a class="btn btn-primary">Share Box</a>
     </div> --}}
</div>