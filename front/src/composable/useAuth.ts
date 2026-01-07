import { useApi } from '@/composable/fetch';
import { API_BASE } from '../services/api';
const urlBase : string = `${API_BASE.replace(/\/$/, '')}/`;

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

    return {data,error,loading,registerUser,loginUser};
}
