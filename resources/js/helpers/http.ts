import axios from 'axios'

const HTTP = axios.create({
    baseURL: 'http://l-vue-p.test', // Remplacez par votre URL de base
    timeout: 10000, // Temps limite en millisecondes
    headers: {
        'Content-Type': 'application/json',
    }
});

export default HTTP;
