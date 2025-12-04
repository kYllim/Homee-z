import { useApi } from '@/composable/fetch';
const urlBase : string = 'http://127.0.0.1:8001/';

export function useAuth() {
    const { data, error, loading, callApi } = useApi();

    async function registerUser(n : string, fn : string, em : string, pwd : string) {
        
        await callApi(
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
        await callApi(
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
