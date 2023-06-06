<tr role="row" class="filter">
<td></td>
<td></td>
<td>{!! Form::text('banner_title', null, ['class'=>'form-control form-filter input-sm form-filter input-sm']) !!}</td>
<td>{!! Form::select('lob',[''=> 'Select'] + $lobList, null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>                        
<td>{!! Form::select('circle', [''=> 'Select'] + $circleList, null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>
<td>{!! Form::select('app_version', [''=> 'Select'] + $appVersionList, null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>
<td>{!! Form::select('banner_screen', [''=> 'Select'] + $screenList , null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>
<td>{!! Form::select('device_os', [''=> 'Select'] + $osList, null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>
<td>{!! Form::select('banner_rank', [''=> 'Select'] + $rankList, null,['class'=>'select2me form-control form-filter input-sm']) !!}</td>
<td></td>
<td></td>
<td> {!!  Form::select('status', ['' => 'Select',1 => trans('admin::messages.active'), 0 =>trans('admin::messages.inactive') ], null, ['id' => 'status_search', 'class'=>'select2me form-control form-filter input-sm']) !!}</td>
    <td>                                 
        {!! Form::button('<i class="fa fa-search"></i>', ['title' => trans('admin::messages.search'), 'class' => 'btn btn-sm yellow filter-submit margin-bottom-5']) !!}
        {!! Form::button('<i class="fa fa-times"></i>', ['title' => trans('admin::messages.reset'), 'class' => 'btn btn-sm red blue filter-cancel margin-bottom-5']) !!}
    </td>
</tr>