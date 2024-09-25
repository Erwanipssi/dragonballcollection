document.addEventListener('DOMContentLoaded', function () {
    const apiUrl = 'https://dragonball-api.com/api/characters';
    const generateBtn = document.getElementById('generateBtn');
    const randomCharacterDiv = document.getElementById('randomCharacter');
    const userId = document.getElementById('userId').value;
    let currentCharacter = null;

    const raceProbabilities = {
        'Human': 33, 
        'Saiyan': 10, 
        'Frieza Race': 14, 
        'Namekian': 1,
        'Android': 16, 
        'Majin': 5, 
        'Angel': 3, 
        'Evil': 5,
        'Nucleico': 5,
        'Nucleico Benigno': 4, 
        'Jiren Race': 4, 
        'Piccolo': 0.5,
        'Zeno': 0.5,
        'Gogeta': 1,
        'Vegetto': 1,
        'Bills': 1
    };

    function selectRaceByProbability() {
        for (const race in raceProbabilities) {
            const randomNum = Math.random() * 100; 
            if (randomNum <= raceProbabilities[race]) {
                return race; 
            }
        }
        return null; 
    }

    async function fetchAllCharacters() {
        let characters = [];
        let currentPage = 1;
        let totalPages = 1;

        try {
            while (currentPage <= totalPages) {
                const response = await fetch(`${apiUrl}?page=${currentPage}`);
                const data = await response.json();
                characters = characters.concat(data.items);
                totalPages = data.meta.totalPages;
                currentPage++;
            }

            return characters;
        } catch (error) {
            console.error("Erreur lors de la récupération des personnages:", error);
        }
    }

    function filterCharactersByRace(characters, race) {
        return characters.filter(character => character.race === race || character.name === race);
    }

    function displayRandomCharacter(characters) {
        const selectedRace = selectRaceByProbability(); 

        if (selectedRace) {
            const charactersOfRace = filterCharactersByRace(characters, selectedRace); 
            if (charactersOfRace.length > 0) {
                const randomIndex = Math.floor(Math.random() * charactersOfRace.length); 
                const character = charactersOfRace[randomIndex];
                currentCharacter = character;

                const characterHtml = `
                    <div class="character-card">
                        <img src="${character.image}" alt="${character.name}" class="character-image">
                        <h2>${character.name}</h2>
                        <p><strong>Espèce :</strong> ${character.race}</p>
                        <p><strong>Genre :</strong> ${character.gender}</p>
                     
                        <button id="saveCharacterBtn">Continuer</button>
                    </div>
                `;

                randomCharacterDiv.innerHTML = characterHtml;
                document.getElementById('saveCharacterBtn').addEventListener('click', saveCharacter);
            } else {
                console.error('Aucun personnage trouvé pour la race sélectionnée.');
            }
        } else {
            console.error('Aucune race sélectionnée après le tirage.');
        }
    }

    async function saveCharacter() {
        if (currentCharacter && userId) {
            try {
                const response = await fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        character: currentCharacter,
                        user_id: userId
                    }),
                });

                const text = await response.text();
                console.log('Réponse brute:', text);

                try {
                    const result = JSON.parse(text);
                    console.log(result);

                    if (result.success) {
                        alert("Personnage enregistré avec succès !");
                        randomCharacterDiv.innerHTML = '';  
                    } else {
                        alert("Erreur lors de l'enregistrement du personnage.");
                    }
                } catch (jsonError) {
                    console.error('Erreur lors de l\'analyse JSON:', jsonError);
                   
                }
            } catch (error) {
                console.error("Erreur lors de l'enregistrement du personnage:", error);
            }
        } else {
            
        }
    }

    generateBtn.addEventListener('click', async () => {
        const characters = await fetchAllCharacters();
        if (characters) {
            displayRandomCharacter(characters);
        }
    });
});
