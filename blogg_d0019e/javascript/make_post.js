/* DENNA JS-FIL DELAS MELLAN BÅDE MAKE_POST.PHP OCH EDIT_POST.PHP PÅ GRUND AV DERAS MÅNGA LIKHETER */

/* TEXT AREA HÖJD. TITEL OCH INNEHÅLL */
const contentTextArea = document.getElementById('post-content')
const titleTextArea = document.getElementById('post-title')

// Uppdatera höjd på textarea för att matcha texten inuti
function updateTitleHeight() {
	titleTextArea.style.height = '' // Återställ höjden
	// Hämta maximal scrollhöjd och sätt textareans ordinarie höjd till denna
	titleTextArea.style.height = Math.min(titleTextArea.scrollHeight) + 'px'
}

// Uppdatera höjd på textarea för att matcha texten inuti
function updateContentHeight() {
	contentTextArea.style.height = '' // Återställ höjden
	// Hämta maximal scrollhöjd och sätt textareans ordinarie höjd till denna
	contentTextArea.style.height = Math.min(contentTextArea.scrollHeight) + 'px'
}

// När sidan laddas, uppdatera höjden på textareor
window.onload = () => {
	// Uppdatera titelfältets höjd
	updateTitleHeight()
	// Uppdatera brödtextfältets höjd
	updateContentHeight()
}

// När användaren gör en inmatning uppdatera höjden för att textarean ska förhålla sig till texten i realtid
titleTextArea.oninput = () => {
	// Uppdatera titelfältets höjd
	updateTitleHeight()
	// Uppdatera brödtextfältets höjd
	updateContentHeight()
}

// När användaren gör en inmatning uppdatera höjden för att textarean ska förhålla sig till texten i realtid
contentTextArea.oninput = () => {
	// Uppdatera titelfältets höjd
	updateTitleHeight()
	// Uppdatera brödtextfältets höjd
	updateContentHeight()
}

/* BILD FÖRHANDSVISNING */
const postCover = document.getElementById('post-cover')
const imgSelectorContainer = document.getElementById('img-selector-container')

// Ifall "data-coverid" parametern på imgSelectorContainer redan är satt, exempelvis i "edit_post.php", använd den bilden
if (
	imgSelectorContainer.dataset.coverid !== undefined &&
	imgSelectorContainer.dataset.coverid !== ''
) {
	// Om bildväljarelementet har ett "data-coverid", hämta den bilden och sätt soms förhandsvisnings
	imgSelectorContainer.style.backgroundImage = `url(../uploads/${imgSelectorContainer.dataset.coverid}`
	imgSelectorContainer.style.backgroundSize = 'cover'
	imgSelectorContainer.classList.add('styled-img-selector-selected')
}

// Uppdatera omslaget ifall användaren väljer en ny fil
postCover.addEventListener('change', (e) => {
	// Hämta den uppladdade filen
	const selectedImg = postCover.files[0]
	// Skapa en bloburl till filen
	const blobURL = URL.createObjectURL(selectedImg)

	// Ändra bildväljarens bakgrundsbild till den uppladdade bilden genom bloburl
	imgSelectorContainer.style.backgroundImage = `url(${blobURL}`
	// Se till så att bilden fyller förhandsvisningsrutan/bildväljaren
	imgSelectorContainer.style.backgroundSize = 'cover'
	imgSelectorContainer.classList.add('styled-img-selector-selected')
})
