<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Channel List</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #000000;
    background-image: url("https://img.freepik.com/free-vector/futuristic-background-design_23-2148503793.jpg");
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    
    padding: 20px;
}

h1 {
    text-align: center;
    background: linear-gradient(135deg, rgb(255, 255, 255), rgb(255, 192, 203), rgb(128, 0, 128));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

label {
    font-weight: 500;
    background: linear-gradient(135deg, rgb(255, 255, 255), rgb(255, 192, 203), rgb(128, 0, 128));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 5px;
}

select, input[type="text"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    flex: 1 1 auto;
}

button {
    padding: 10px 15px;
    font-size: 16px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    flex: 0 0 auto;
}

button:hover {
    background-color: #0056b3;
}

#channels {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 20px;
}

/* Tablet screens */
@media (min-width: 768px) {
    #channels {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

/* Desktop screens */
@media (min-width: 1024px) {
    #channels {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

.channel {
    background: transparent;
    border: 1.5px solid #000;
    border-radius: 5px;
    padding: 15px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px 5px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(9px);
    border: 2px solid rgba(255, 255, 255, .2);
    filter: contrast(100%);
    transition: transform 0.2s;
}

.channel:hover {
    background: LightPink;
    box-shadow: 5px 5px 10px #000;
    transform: scale(1.05);
}

.channel img {
    max-width: 100%;
    border-radius: 5px;
}

.channel h2 {
    text-align: center;
    font-size: 14px;
    margin: 10px 0;
    color: #fff;
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Tablet screens */
@media (min-width: 768px) {
    .channel h2 {
    text-align: center;
    font-size: 20px;
    margin: 10px 0;
    color: #fff;
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }
}

/* Desktop screens */
@media (min-width: 1024px) {
    .channel h2 {
    text-align: center;
    font-size: 20px;
    margin: 10px 0;
    color: #fff;
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    }
}

.channel a {
    display: block;
    font-size: 12px;
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #FF69B4;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
}

.channel a:hover {
    background-color: #FFC0CB;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px 5px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
    <h1>TATAPLAY+</h1>

    <form id="filterForm">
        <label for="genre">Filter by Genre:</label>
        <select id="genre" name="genre" onchange="filterChannels()">
            <option value="">All</option>
            <!-- Dynamically populated -->
        </select>

        <label for="language">Filter by Language:</label>
        <select id="language" name="language" onchange="filterChannels()">
            <option value="">All</option>
            <!-- Dynamically populated -->
        </select>

        <label for="search">Search:</label>
        <input type="text" id="search" name="search" oninput="filterChannels()">
    </form>

    <div id="channels">
        <!-- Channel list populated here -->
    </div>

    <script>
        let channels = [];
        fetch('channel.json')
            .then(response => response.json())
            .then(data => {
                if (data.data && data.data.channels) {
                    channels = data.data.channels;
                    populateFilters();
                    displayChannels(channels);
                } else {
                    console.error('Invalid JSON format');
                }
            });

        function populateFilters() {
            const genreSet = new Set();
            const languageSet = new Set();
            
            channels.forEach(channel => {
                if (channel.genres) {
                    channel.genres.forEach(genre => genreSet.add(genre));
                }
                if (channel.languages) {
                    channel.languages.forEach(language => languageSet.add(language));
                }
            });

            const genreSelect = document.getElementById('genre');
            genreSet.forEach(genre => {
                const option = document.createElement('option');
                option.value = genre;
                option.text = genre;
                genreSelect.appendChild(option);
            });

            const languageSelect = document.getElementById('language');
            languageSet.forEach(language => {
                const option = document.createElement('option');
                option.value = language;
                option.text = language;
                languageSelect.appendChild(option);
            });
        }

        function filterChannels() {
            const genreFilter = document.getElementById('genre').value.toLowerCase();
            const languageFilter = document.getElementById('language').value.toLowerCase();
            const searchFilter = document.getElementById('search').value.toLowerCase();

            const filteredChannels = channels.filter(channel => {
                const matchesGenre = genreFilter === '' || (channel.genres && channel.genres.some(g => g.toLowerCase() === genreFilter));
                const matchesLanguage = languageFilter === '' || (channel.languages && channel.languages.some(l => l.toLowerCase() === languageFilter));
                const matchesSearch = searchFilter === '' || channel.name.toLowerCase().includes(searchFilter);

                return matchesGenre && matchesLanguage && matchesSearch;
            });

            displayChannels(filteredChannels);
        }

        function displayChannels(channels) {
            const channelsContainer = document.getElementById('channels');
            channelsContainer.innerHTML = '';

            channels.forEach(channel => {
                const genresList = channel.genres ? channel.genres.join(', ') : 'N/A';
                const languagesList = channel.languages ? channel.languages.join(', ') : 'N/A';

                const channelElement = document.createElement('div');
                channelElement.classList.add('channel');

                channelElement.innerHTML = `
                    <img src="${channel.logo_url}" alt="${channel.name}">
                    <h2>${channel.name}</h2>
                    <a href="play.php?id=${channel.id}">Watch Now</a>
                `;

                channelsContainer.appendChild(channelElement);
            });
        }
    </script>
</body>
</html>
