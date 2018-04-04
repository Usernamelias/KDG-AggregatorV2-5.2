<h2 class="tableHeadings">{{ $aggregatedEntriesTableHeadline }}</h2>
<div class="table-responsive">
  <table class="table aggregateTable table-hover">
    <tr class="headerRow">
      <th>TIME</th>
      <th>Project</th>
      <th>TASK/TICKET</th>
      <th>DESCRIPTION</th>
      <th>ACTIONS</th>
    </tr>

    @foreach($aggregatedEntries as $aggregatedEntry)
      <tr>
        <td>{{ $aggregatedEntry['total']}}</td>
        <td>{{ $aggregatedEntry['project_name']}}</td>
        <td>{{ $aggregatedEntry['task']}}</td>
        <td>{{ $aggregatedEntry['concatDescription']}}</td>
        <td class="sync">
        <button type="submit"
        class="sync-button"
        data-sync='{{ $aggregatedEntry["project_name"] }} {{ $aggregatedEntry["task"] }}'
        data-total='{{ $aggregatedEntry["total"] }}'
        data-aggregated-project-name='{{ $aggregatedEntry["project_name"] }}'
        data-aggregated-task='{{ $aggregatedEntry["task"] }}'
        data-aggregated-description='{{ $aggregatedEntry["concatDescription"] }}'
        data-aggregated-billable='{{ $aggregatedEntry["billable"] }}'
        data-aggregated='{{ $aggregatedEntry }}'
        data-entry-date='{{ $entryDate }}'
        data-user-id='{{ $aggregatedEntry["user_id"] }}'
        ><i class="fas fa-sync"></i>
        <span>SYNC</span></button></td>
      </tr>
    @endforeach

    <tr class="totalRow">
      <td>Total</td>
      <td>{{ $aggregatedTotals }}</td>
      <td></td>
      <td></td>
      <td class="sync"><form method="post">{{ csrf_field() }}
      <button
      class="sync-button"
      data-sync-all='sync_all'
      data-aggregated='{{ $aggregatedEntries }}'
      data-entry-date='{{ $entryDate }}'
      ><i class="fas fa-sync"></i> <span>SYNC ALL</span></button></form></td>
    </tr>
  </table>
</div>

@push('body')
  <script type="text/javascript">
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

        $('[data-sync]').click(function (e) {

          e.preventDefault();

          var thisButton = $(this);
          $(this).children("i").addClass("fa-spin");

          var total = $(this).data('total');
          var project_name = $(this).data('aggregated-project-name');
          var task = $(this).data('aggregated-task');
          var concatDescription = $(this).data('aggregated-description');
          var billable = $(this).data('aggregated-billable');
          var entryDate = $(this).data('entry-date');
          var userID = $(this).data('user-id');

          var CSRF_TOKEN = '{{csrf_token()}}';

          $.ajax({

            type:'POST',
            url:'/work-done',
            data: {
              total:total,
              project_name:project_name,
              task:task,
              concatDescription:concatDescription,
              entryDate:entryDate,
              billable:billable,
              _token: CSRF_TOKEN
            },
            success: function(data){
              //alert(data.success);
              $("i").removeClass("fa-spin");

              $('button[data-sync="' + project_name + ' ' + task +'"]').prop("disabled",true);
              $('button[data-sync="' + project_name + ' ' + task +'"]').addClass("disabled_button");

              $('i[data-edit-ptu-id="' + project_name + ' ' + task + ' ' + userID+'"]').prop("disabled",true);
              $('i[data-edit-ptu-id="' + project_name + ' ' + task + ' ' + userID+'"]').addClass("disabled_button");

              $('i[data-delete-ptu-id="' + project_name + ' ' + task + ' ' + userID+'"]').prop("disabled",true);
              $('i[data-delete-ptu-id="' + project_name + ' ' + task + ' ' + userID+'"]').addClass("disabled_button");

              $('tr[data-tr-ptu-id="' + project_name + ' ' + task + ' ' + userID+'"]').css("text-decoration", "line-through");

            },
            failure: function(data){
              $("i").removeClass("fa-spin");
            }
          });

        });

        $('[data-sync-all]').click(function (e) {

          e.preventDefault();

          var thisButton = $(this);
          $(this).children("i").addClass("fa-spin");

          var aggregatedEntries = $(this).data('aggregated');
          var entryDate = $(this).data('entry-date');
          var CSRF_TOKEN = '{{csrf_token()}}';

          $.ajax({

            type:'POST',
            url:'/work-done',
            data: {
              aggregatedEntries:aggregatedEntries,
              entryDate:entryDate,
              _token: CSRF_TOKEN
            },
            success: function(data){
              //alert(data.success);
              $("i").removeClass("fa-spin");

              $('[data-sync-all]').prop("disabled",true);
              $('[data-sync-all]').addClass("disabled_button");

              $('[data-sync]').prop("disabled",true);
              $('[data-sync]').addClass("disabled_button");

              window.setTimeout(function(){
                  window.location.href = window.location.href;
              },0);

            },
            failure: function(data){
              $("i").removeClass("fa-spin");
            }
          });

        });

  </script>

@endpush
