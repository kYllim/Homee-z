import { useApi } from '@/composable/fetch';
const urlBase : string = 'http://localhost:3000/';

export async function registerUser(n : string, fn : string, em : string, pwd : string) : Promise<any> {
    const { data, error, loading, callApi } = useApi();
    await callApi(urlBase + "api/register", {
        method: "POST",
        body: {
            name :  n,
            firstname : fn,
            email : em,
            password : pwd,
        }
    })

    if (error.value) {
        throw new Error(error.value);
    }

    return data.value;
}


