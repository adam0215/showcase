<script>
	import SideNav from '$lib/components/SideNav.svelte'
	import '../app.css'
</script>

<div class="flex h-full pr-12 text-gray-700">
	<SideNav />
	<main class="relative h-full w-full py-10">
		<slot />
	</main>
</div>
