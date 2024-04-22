<script lang="ts">
	import Icon from '@iconify/svelte'

	export let clickable: PropClickableTokens
	export let removable: PropRemovableTokens = false
	export let onRemove: () => void = () => {}

	let showRemoveBtn = false
</script>

{#if showRemoveBtn && removable}
	<span class="group relative" title="Remove Dynamic Token">
		<!-- TOUCH AREA -->
		<span
			class="peer absolute left-[2px] top-0 z-10 aspect-square w-[1.5em] cursor-pointer outline-none"
			tabindex="0"
			role="button"
			on:click={() => onRemove()}
			on:keypress={() => onRemove()}
			on:mouseleave={() => (showRemoveBtn = false)}
			on:focusout={() => (showRemoveBtn = false)}
		/>

		<span
			class="relative mx-2 inline-block aspect-square h-[1em] rounded-full bg-emerald-100 align-middle ring-emerald-500"
			tabindex="0"
			role="button"
		>
			<Icon icon="ic:baseline-remove" class="text-emerald-500" />
		</span>
	</span>
{:else}
	<span class="group relative">
		<!-- TOUCH AREA -->
		<span
			class="absolute inset-0 z-10 h-9 w-full cursor-pointer outline-none"
			tabindex="0"
			role="button"
			on:mouseover={() => (removable ? (showRemoveBtn = true) : null)}
			on:focus={() => (removable ? (showRemoveBtn = true) : null)}
		/>

		<span
			class="mx-1 inline-flex items-center rounded-full bg-emerald-100 px-4 py-0 align-middle text-emerald-500 transition-all duration-200"
			class:hover:bg-emerald-300={clickable}
			class:cursor-pointer={clickable}
			class:hover:text-emerald-100={clickable}
			><Icon icon="solar:menu-dots-linear" width="20" />
		</span>
	</span>
{/if}
