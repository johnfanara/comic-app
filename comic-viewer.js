let currentPageIndex = 0;

async function openComic(fileUrl) {
  const comicContainer = document.getElementById('comic-container');
  comicContainer.innerHTML = '';

  const response = await fetch(fileUrl);
  const buffer = await response.arrayBuffer();

  const fileExtension = fileUrl.split('.').pop().toLowerCase();
  let archive;
  if (fileExtension === 'cbr') {
    archive = await LibArchive.open({ buffer });
  } else if (fileExtension === 'cbz') {
    archive = await JSZip.loadAsync(buffer);
  } else {
    alert("Unsupported file type.");
    return;
  }

  const files = await getFilesFromArchive(archive, fileExtension);
  const comicFile = files.shift();

  if (!comicFile) {
    alert("No suitable file found in the archive.");
    return;
  }

  const blob = new Blob([comicFile.content], { type: 'image/jpeg' });
  const url = URL.createObjectURL(blob);

  const img = document.createElement('img');
  img.src = url;
  img.style.width = '100%';
  img.style.display = 'block';
  img.style.marginBottom = '1rem';
  img.addEventListener('click', () => {
    loadNextPage(files, comicContainer, archive);
  });

  comicContainer.appendChild(img);

  // Add event listener for arrow key navigation
  document.addEventListener('keydown', (event) => handleArrowKeys(event, files, comicContainer, archive));
}

async function getFilesFromArchive(archive, fileExtension) {
  if (fileExtension === 'cbr') {
    return archive.getFiles();
  } else if (fileExtension === 'cbz') {
    const files = [];
    const zipEntries = await archive.files;
    for (const entry of zipEntries) {
      if (entry.name.toLowerCase().endsWith('.jpg') || entry.name.toLowerCase().endsWith('.jpeg') || entry.name.toLowerCase().endsWith('.png') || entry.name.toLowerCase().endsWith('.gif')) {
        files.push(entry);
      }
    }
    return files;
  }
}

async function loadNextPage(files, comicContainer, archive) {
  if (files.length === 0) {
    alert("End of comic book.");
    return;
  }

  const nextPageFile = files.shift();
  const nextPageBlob = new Blob([nextPageFile.content], { type: 'image/jpeg' });
  const nextPageUrl = URL.createObjectURL(nextPageBlob);

  const nextPageImg = document.createElement('img');
  nextPageImg.src = nextPageUrl;
  nextPageImg.style.width = '100%';
  nextPageImg.style.display = 'block';
  nextPageImg.style.marginBottom = '1rem';
  nextPageImg.addEventListener('click', () => {
    loadNextPage(files, comicContainer, archive);
  });

  comicContainer.innerHTML = '';
  comicContainer.appendChild(nextPageImg);
}

function handleArrowKeys(event, files, comicContainer, archive) {
  if (event.key === 'ArrowRight') {
    loadNextPage(files, comicContainer, archive);
  } else if (event.key === 'ArrowLeft') {
    alert('Previous page navigation is not supported. Click on the image to proceed to the next page.');
  }
}