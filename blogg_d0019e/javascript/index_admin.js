const bioCharCounter = document.getElementById('bio-character-counter')
const bioInput = document.getElementById('biography')
const bioSaveBtn = document.getElementById('save-bio-btn')

// Lägg till en lyssnare för inmatning i bio-inmatningsfältet
bioInput.addEventListener('input', (e) => {
	// Visa spara-bio-knapp
	bioSaveBtn.classList.remove('d-none')

	// Teckengräns för bio
	const charLimit = 240

	// Antalet tecken i bio-inmatningsfältet
	const bioCharAmount = e.target.value.length
	// Uppdatera antalstexten
	bioCharCounter.innerText = `${bioCharAmount}/${charLimit}`

	// Gör texten till alarmerande när antalet tecken överskrider teckengränsen (240t för tillfället)
	if (bioCharAmount > charLimit) {
		bioCharCounter.classList.add('alerting-text')
	} else {
		bioCharCounter.classList.remove('alerting-text')
	}
})

// Ta bort-inlägg-knapp
const deleteButtons = document.querySelectorAll('.remove-post-btn')
// Pop-up dialog
const dialog = document.getElementById('post-deletion-dialog')
// Knappar inuti dialogen
const dialogCloseBtn = dialog.querySelector('#close-dialog-btn')
const dialogSubmitBtn = dialog.querySelector('#submit-dialog-btn')
const dialogTitlePreview = document.getElementById('dialog-post-title-preview')
// Ev. inlägg valt att ta bort
let postToDelete = null
// Ev. inläggselement i HTML-listan som ska tas bort i gränssnittet
let listItemToDelete = null

// Lägg till klicklyssnare på alla ta bort-inlägg knappar
for (const button of deleteButtons) {
	button.addEventListener('click', (e) => {
		// Välj omfattande containern med inläggs-id:t för att kunna komma åt detta senare
		const buttonContainer = e.target.closest('div[data-postid]')
		// Inläggs-id
		const clickedPostID = buttonContainer.dataset.postid
		// Inläggstitel
		const postTitlePreview = buttonContainer.dataset.posttitle
		const mainListItem = buttonContainer.parentElement
		// Sätt postToDelete till det klicka inläggets ID
		postToDelete = clickedPostID
		listItemToDelete = mainListItem
		// Uppdatera texten i dialogen till titeln på inlägget som ska tas bort
		dialogTitlePreview.innerText = `"${postTitlePreview}"`
		// Visa dialogruta
		dialog.showModal()
	})
}

dialogCloseBtn.addEventListener('click', () => {
	// Återställ postToDelete vid stängning
	postToDelete = null
	// Stäng dialogruta
	dialog.close()
})

dialogSubmitBtn.addEventListener('click', (e) => {
	// Ta bort inlägg ifall variabeln har ett värde
	if (postToDelete) {
		deletePost(postToDelete)
		listItemToDelete.remove()
	}
})

// Funktion för att göra förfrågan till API:n om att ta bort ett inlägg
function deletePost(postID) {
	fetch('../api/api_post_delete.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		// Data till API
		body: JSON.stringify({
			postID: postID,
		}),
	})
		.then((response) => {
			// Om svaret inte är ok
			if (!response.ok) {
				throw response
			}
			// Ok svar
			return response.json()
		})
		.then((data) => {
			// Framgångsmeddelande
			console.log('Post deleted successfully:', data)
		})
		.catch((error) => {
			// Felmeddelande
			console.error('Error deleting post:', error)
		})
}
