@extends('back-end.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    function deleteConfirmation(id) {
        swal({
            title: "Delete?",
            text: "Are You Sure You Want To Delete...?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {

            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'POST',
                    url: "{{url('/deleteSingleUser')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {

                        if (results.success === true) {
                            swal("Done!", results.message, "success").then(function(){
                                                                    location.reload();
                                                                });
                            
                        } else {
                            swal("Error!", results.message, "error");
                        }

                    }
                });

            } else {
                e.dismiss;
            }

        }, function (dismiss) {
            return false;
        })
    }
</script>
<section class="wt-haslayout wt-dbsectionspace" id="profile_settings">
    <script type="text/javascript">
        function abc()
        {
                $(document).ready(function () 
                {
                        $('#master').on('click', function(e) 
                        {
                             if($(this).is(':checked',true))  
                             {
                                $(".sub_chk").prop('checked', true);  
                             } 
                             else 
                             {  
                                $(".sub_chk").prop('checked',false);  
                             }  
                        });
                        $('.delete_all').on('click', function(e) 
                        {
                            var allVals = [];  
                            $(".sub_chk:checked").each(function() 
                            {  
                                allVals.push($(this).attr('data-id'));
                            });  
                            if(allVals.length <=0)  
                            {  
                                 swal({
                                        title: "Delete?",
                                        text: "Please Select Row...",
                                        type: "warning",
                                        confirmButtonText: "OK",
                                        reverseButtons: !0
                                    });
                            }  
                            else 
                            {  
                                var check = confirm("Are you sure you want to delete this row?");  
                                if(check == true)
                                {  
                                    var join_selected_values = allVals.join(","); 
                                    $.ajax({
                                        url: $(this).data('url'),
                                        type: 'DELETE',
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                        data: 'ids='+join_selected_values,
                                        success: function (data) {
                                            if (data['success']) {
                                                $(".sub_chk:checked").each(function() {  
                                            $(this).parents("tr").remove();
                                        });
                                        alert(data['success']);
                                    } else if (data['error']) {
                                        alert(data['error']);
                                    } else {
                                        alert('Whoops Something went wrong!!');
                                    }
                                },
                                error: function (data) 
                                {
                                    alert(data.responseText);
                                }
                            });
                            $.each(allVals, function( index, value ) 
                            {
                                $('table tr').filter("[data-row-id='" + value + "']").remove();
                            });
                        }  
                    }  
                });
                $('[data-toggle=confirmation]').confirmation({
                    rootSelector: '[data-toggle=confirmation]',
                    onConfirm: function (event, element) 
                    {
                        element.trigger('confirm');
                    }
                });
                $(document).on('confirm', function (e) 
                {
                    var ele = e.target;
                    e.preventDefault();
                    $.ajax({
                        url: ele.href,
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (data) {
                            if (data['success']) {
                                $("#" + data['tr']).slideUp("slow");
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                    return false;
                });
            });
        }
    </script>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 float-right">
                @if (Session::has('message'))
                    <div class="flash_msg">
                        <flash_messages :message_class="'success'" :time ='5' :message="'{{{ Session::get('message') }}}'" v-cloak></flash_messages>
                    </div>
                @endif
                <div class="wt-dashboardbox">
                    <div class="wt-dashboardboxtitle wt-titlewithsearch">
                        <h2>{{{ trans('lang.manage_users') }}}</h2>
                        <form class="wt-formtheme wt-formsearch">
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="keyword" value="{{{ !empty($_GET['keyword']) ? $_GET['keyword'] : '' }}}"
                                        class="form-control" placeholder="{{{ trans('lang.ph_search_users') }}}">
                                    <button type="submit" class="wt-searchgbtn"><i class="lnr lnr-magnifier"></i></button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="wt-dashboardboxcontent wt-categoriescontentholder">
                        <table class="wt-tablecategories">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="wt-actionbtn">
                                            <a href="javascript:void()" class="wt-deleteinfo wt-skillsaddinfo delete_all" data-url="{{ url('myproductsDeleteAll') }}" onclick="abc();"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </th>
                                    <th>{{{ trans('lang.user_name') }}}</th>
                                    <th>{{{ trans('lang.role') }}}</th>
                                    <th>{{{ trans('lang.action') }}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user_data)
                                    @php $user = \App\User::find($user_data['id']); @endphp
                                    @if ($user->getRoleNames()->first() != 'admin')
                                        <tr>
                                            <td><input type="checkbox" class="sub_chk" data-id="{{$user->id}}" {{ ++$key }}></td>
                                            <td>{{{ ucwords(\App\Helper::getUserName($user->id)) }}}</td>
                                            <td>{{ $user->getRoleNames()->first() }}</td>
                                            <td>
                                                <div class="wt-actionbtn">
                                                    <a href="javascript:void()" class="wt-deleteinfo wt-skillsaddinfo" onclick="deleteConfirmation({{$user->id}});"><i class="fa fa-trash"></i></a>
                                                    <a href="{{ url('profile/'.$user->slug) }}" class="wt-addinfo wt-skillsaddinfo"><i class="lnr lnr-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        @if ( method_exists($users,'links') )
                            {{ $users->links('pagination.custom') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
