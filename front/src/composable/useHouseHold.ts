import { useApi } from '@/composable/fetch';
const urlBase : string = 'http://127.0.0.1:8000/';

export function useHouseHold() {
    const { data, error, loading, callApi } = useApi();


    async function CreateHouseHold (n : string, t : string) {
        await callApi(
            urlBase + "api/CreateHouseHold", 
            {
                method: "POST",
                body: {
                    name : n,
                },
                token: t,
            }
        );
    }

    return {data,error,loading,CreateHouseHold};
}
