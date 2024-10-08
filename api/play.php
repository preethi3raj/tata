<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tataplay Live</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        #player {
            position: absolute;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body>
<div id="player"></div>

<script src="https://content.jwplatform.com/libraries/KB5zFt7A.js"></script>
<script>
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

let currentKeyId = '';
let currentKey = '';
let stretchModes = ['uniform', 'exactfit', 'fill', 'none'];
let currentStretchModeIndex = 0;

async function setupPlayer(channelId) {
    try {
        const response = await fetch(`get.php?id=${channelId}`);
        const data = await response.json();

        const videoUrl = data.data.channel_url;
        const videoTitle = data.data.channel_name;
        const keyid = data.data.keyId;
        const key = data.data.key;

        const playerInstance = jwplayer("player").setup({
            controls: true,
            sharing: true,
            displaytitle: true,
            autoplay: true,
            displaydescription: true,
            crossorigin: "anonymous",
            abouttext: "Video Player By DRMLIVE",
            aboutlink: "https://t.me/sardariptv",
            skin: {
                name: "netflix"
            },
            logo: {
                file: "https://i.postimg.cc/CK89S084/sardar-iptv-high-resolution-logo-1.png",
                link: "https://t.me/sardariptv"
            },
            captions: {
                color: "#FFF",
                fontSize: 14,
                backgroundOpacity: 0,
                edgeStyle: "raised"
            },
            playlist: [
                {
                    title: videoTitle,
                    description: "You're Watching",
                    image: "https://i.postimg.cc/CK89S084/sardar-iptv-high-resolution-logo-1.png",
                    sources: [
                        {
                            file: videoUrl,
                            type: "dash",
                            label: "1080p",
                            default: true,
                            drm: { 
                                "clearkey": {  
                                    "keyId": keyid,
                                    "key": key 
                                } 
                            }
                        }
                    ]
                }
            ],
            stretching: stretchModes[currentStretchModeIndex],
            advertising: {
                client: "vast",
                schedule: [
                    {
                        offset: "pre",
                        tag: ""
                    }
                ]
            }
        });

        playerInstance.on('ready', function() {
            playerInstance.addButton(
                "https://i.postimg.cc/VkkRMSMJ/ratio.webp",
                "Toggle Aspect Ratio",
                function() {
                    currentStretchModeIndex = (currentStretchModeIndex + 1) % stretchModes.length;
                    let selectedStretchMode = stretchModes[currentStretchModeIndex];
                    
                    playerInstance.setConfig({
                        stretching: selectedStretchMode
                    });
                },
                "aspectRatioButton",
                "control-bar",
                "right"
            );
        });
    } catch (error) {
        console.error('Error setting up player:', error);
        alert("Error loading player. Please try again later.");
    }
}

const channelId = getQueryParam('id');

if (channelId) {
    setupPlayer(channelId);
} else {
    alert('No channel ID provided in the URL');
}
</script>
</body>
</html>
