<script lang="ts">
	import DynamicTextToken from './DynamicTextToken.svelte'
	import Icon from '@iconify/svelte'
	import PrimaryButton from './PrimaryButton.svelte'
	import SmallTag from './SmallTag.svelte'

	export let clickableTextTokens: PropClickableTokens

	// Reference for dialog element
	let dialogRef: HTMLDialogElement
</script>

<dialog
	on:close
	bind:this={dialogRef}
	class="w-96 max-w-sm rounded-xl ring-2 ring-emerald-500 backdrop:bg-white/60 backdrop:backdrop-blur-sm"
>
	<div class="flex max-w-sm flex-col justify-between gap-6 rounded-xl p-4">
		<div class="flex flex-col gap-2">
			<span class="flex w-full justify-between">
				<h3 class="text-xl font-semibold text-emerald-600">Example Dynamic Dialog</h3>
				<Icon icon="solar:atom-linear" width="32" class="text-emerald-300" />
			</span>
			<div class="flex gap-2">
				<SmallTag text="Utility" />
				<SmallTag text="Language" />
			</div>
		</div>
		<p class="text-gray-700">
			Define <DynamicTextToken clickable={clickableTextTokens} /> to a five year old.
		</p>
		<div class="flex gap-4">
			<PrimaryButton text="Customize & Use" />
		</div>
	</div>
</dialog>
