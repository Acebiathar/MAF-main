@forelse($recentActivity as $event)
  <a class="admin-feed-row" href="{{ $event['url'] }}"><span class="admin-feed-icon admin-tone-{{ $event['tone'] }}"><i class="bi {{ $event['icon'] }}" aria-hidden="true"></i></span><span class="admin-feed-copy"><strong>{{ $event['title'] }}</strong><span>{{ $event['detail'] }}</span><time datetime="{{ \Illuminate\Support\Carbon::parse($event['time'])->toIso8601String() }}">{{ \Illuminate\Support\Carbon::parse($event['time'])->diffForHumans() }}</time></span></a>
@empty
  <p class="admin-empty">No activity recorded yet.</p>
@endforelse
