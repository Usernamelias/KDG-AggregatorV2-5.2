<h2 class="tableHeadings">{{ $singleEntriesTableHeadline }}</h2>
      <div class="table-responsive">
        <table class="table table-hover">
          <tr class="headerRow">
            <th>TIME</th>
            <th>DURATION</th>
            <th>BILLABLE</th>
            <th>PROJECT</th>
            <th>TASK/TICKET</th>
            <th>DESCRIPTION</th>
            <th>ACTIONS</th>
          </tr>
          @foreach($allEntries as $entry)
            <tr data-tr-ptu-id="{{ $entry['project_name'] }} {{ $entry['task'] }} {{ $entry['user_id'] }}">
              <td>@if($entry['start_time'] === null) @else {{ $entry['formatted_start_time'] }} - {{ $entry['formatted_end_time']  }} @endif</td>
              <td>{{ $entry['duration'] }}</td>
              <td>
              @if( $entry['billable'] === 1)
              Yes
              @else
              No
              @endif</td>
              <td>{{ $entry['project_name'] }}</td>
              <td>{{ $entry['task'] }}</td>
              <td>{{ $entry['description'] }}</td>
              <td>
                <i
                data-toggle="modal"
                data-target="#editModal"
                data-edit-id="{{ $entry['id'] }}"
                data-textarea="{{ $entry['description'] }}"
                data-task="{{ $entry['task'] }}"
                data-project-name="{{ $entry['project_name'] }}"
                data-billable="{{ $entry['billable'] }}"
                data-duration="{{ $entry['duration'] }}"
                data-start-time="{{ $entry['start_time'] }}"
                data-end-time="{{ $entry['end_time'] }}"
                data-edit-ptu-id="{{ $entry['project_name'] }} {{ $entry['task'] }} {{ $entry['user_id'] }}"
                class="fas fa-pencil-alt"
                ></i>
                <i data-toggle="modal" data-target="#deleteModal" data-delete-id="{{ $entry['id'] }}"
                data-delete-ptu-id="{{ $entry['project_name'] }} {{ $entry['task'] }} {{ $entry['user_id'] }}" class="fas fa-trash"></i>
              </td>
            </tr>
          @endforeach
          <tr class="totalRow">
            <td>Total</td>
            <td>{{ $durationTotal }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </div>
