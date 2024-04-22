<script lang="ts">
	import Icon from '@iconify/svelte'
	import { currentRouteStore } from '../../routes/routeStore'
	import { afterNavigate } from '$app/navigation'
	import { page } from '$app/stores'

	let currentRoute: CurrentRouteObj

	// Runs on navigation
	afterNavigate(() => {
		// Update local state
		currentRoute = {
			// If homepage ( / ) return '/' else return route-name
			routeName: $page.url.pathname.split('/')[1] === '' ? '/' : $page.url.pathname.split('/')[1],
			routePath: $page.url.pathname
		}
		// Update store
		currentRouteStore.set(currentRoute)
	})

	const defaultItemStyling =
		'flex items-center gap-2 text-lg hover:bg-emerald-100 hover:text-emerald-700 hover:py-2 hover:px-4 transition-all rounded-md duration-300'
	const activeItemStyling =
		'flex items-center gap-2 text-lg bg-emerald-100 text-emerald-700 py-2 px-4 transition-all rounded-md duration-300'

	const getRouteIsActive = (routeName: string) =>
		routeName === currentRoute?.routeName ? true : false
</script>

<aside class="h-full w-full max-w-xs px-12 py-10">
	<nav>
		<ul class="flex flex-col gap-14">
			<li>
				<a href="/" class={'font-logo text-2xl text-gray-900'}
					>PROMPTLIB<span class="text-emerald-500">.</span></a
				>
			</li>
			<div class="flex flex-col gap-6 text-lg">
				{#key currentRoute}
					<li>
						<!-- LIBRARY / HOME -->
						<a href="/" class={getRouteIsActive('/') ? activeItemStyling : defaultItemStyling}>
							<Icon icon="solar:library-linear" width={24} />
							Library
						</a>
					</li>
					<li>
						<!-- CHAT -->
						<a
							href="/chat"
							class={getRouteIsActive('chat') ? activeItemStyling : defaultItemStyling}
						>
							<Icon icon="solar:chat-line-linear" width={24} />
							Chat
						</a>
					</li>
					<li>
						<!-- CHAT -->
						<a
							href="/compositor/create"
							class={getRouteIsActive('compositor') ? activeItemStyling : defaultItemStyling}
						>
							<Icon icon="solar:pen-new-square-linear" width={24} />
							Compositor
						</a>
					</li>
				{/key}
			</div>
		</ul>
	</nav>
</aside>
