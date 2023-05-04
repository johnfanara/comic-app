async function openComicBook(filePath) {
    const archive = await loadArchive(filePath);
    const files = await listFilesInArchive(archive);
    const comicFile = findComicFile(files);

    if (!comicFile) {
        alert("No suitable file found in the archive.");
        return;
    }

    const fileData = await readFileFromArchive(archive, comicFile);
    const container = document.getElementById("comic-container");

    if (comicFile.type === 'pdf') {
        const pdfDocument = await pdfjsLib.getDocument({ data: fileData }).promise;
        const pdfViewer = new pdfjsViewer.PDFViewer({ container, document: pdfDocument });
    } else {
        displayComicImages(container, files, archive);
    }
}

async function loadArchive(filePath) {
    const response = await fetch(filePath);
    const data = await response.arrayBuffer();
    const fileExtension = filePath.split('.').pop().toLowerCase();
    let archive;

    if (fileExtension === 'cbr') {
        archive = await LibArchive.open({ buffer: data });
    } else if (fileExtension === 'cbz') {
        archive = await JSZip.loadAsync(data);
    } else {
        alert("Unsupported file type.");
        return;
    }

    return archive;
}

async function listFilesInArchive(archive) {
    if (archive instanceof LibArchive) {
        const files = [];
        let entry = await archive.readEntry();
        while (entry) {
            files.push(entry);
            entry = await archive.readEntry();
        }
        return files;
    } else {
        const files = [];
        archive.forEach((path, file) => {
            files.push(file);
        });
        return files;
    }
}

function findComicFile(files) {
    const pdfFile = files.find(file => file.name.toLowerCase().endsWith('.pdf'));
    if (pdfFile) return pdfFile;

    const imageExtensions = ["jpg", "jpeg", "png", "gif"];
    const imageFile = files.find(file => {
        const fileExtension = file.name.split(".").pop().toLowerCase();
        return imageExtensions.includes(fileExtension);
    });

    return imageFile;
}

async function readFileFromArchive(archive, file) {
    if (archive instanceof LibArchive) {
        await archive.extract(file);
        const data = await archive.readFileData(file);
        return data;
    } else {
        return await file.async("arraybuffer");
    }
}

function displayComicImages(container, files, archive) {
    const images = files.filter(file => {
        const fileExtension = file.name.split(".").pop().toLowerCase();
        return ["jpg", "jpeg", "png", "gif"].includes(fileExtension);
    });

    container.innerHTML = '';

    const loadImage = async (file) => {
        const data = await readFileFromArchive(archive, file);
        const blob = new Blob([data], { type: 'image/jpeg' });
        const url = URL.createObjectURL(blob);

        const img = document.createElement('img');
        img.src = url;
        img.style.width = '100%';
        img.style.display = 'block';
        img.style.marginBottom = '1rem';

        return img;
    };

    (async () => {
        for (const file of images) {
            const img = await loadImage(file);
            container.appendChild(img);
        }
    })();
}