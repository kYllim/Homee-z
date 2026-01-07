import { useApi } from '@/composable/fetch';
import { GetCookie } from '@/services/cookie';
const urlBase : string = 'http://127.0.0.1:8000/';

export function useAuth() {
    const { data, error, loading, callApi } = useApi();

    async function registerUser(n : string, fn : string, em : string, pwd : string) {

        return await callApi(
            urlBase + "api/register",
            {
                method: "POST",
                body: {
                    name :  n,
                    firstname : fn,
                    email : em,
                    password : pwd,
                }
            }
        );
    }

    async function loginUser(em : string, pwd : string) {
        return await callApi(
            urlBase + "api/login",
            {
                method: "POST",
                body: {
                    email : em,
                    password : pwd,
                }
            }
        );
    }

    /**
     * Décoder le token JWT et récupérer les informations de l'utilisateur
     */
    function getUserFromToken() {
        const token = GetCookie('token');
        if (!token) return null;

        try {
            // Décoder le payload du JWT (partie entre les deux points)
            const payload = token.split('.')[1];
            if (!payload) return null;

            const decodedPayload = JSON.parse(atob(payload));

            // Le backend envoie firstName et lastName dans le token
            const firstName = decodedPayload.firstName || '';
            const lastName = decodedPayload.lastName || '';
            const fullName = `${firstName} ${lastName}`.trim();

            return {
                id: decodedPayload.id || decodedPayload.sub,
                email: decodedPayload.email || decodedPayload.username,
                firstName: firstName,
                lastName: lastName,
                fullName: fullName || decodedPayload.username || 'Utilisateur'
            };
        } catch (e) {
            console.error('Erreur lors du décodage du token:', e);
            return null;
        }
    }

    return {data,error,loading,registerUser,loginUser,getUserFromToken};
}
