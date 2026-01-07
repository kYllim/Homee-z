import { useApi } from '@/composable/fetch';
import type { HouseHoldRoleEnum } from '@/models';
import { API_BASE } from '../services/api';

const urlBase : string = `${API_BASE.replace(/\/$/, '')}/`;

export function useHouseHold() {
    const { data, error, loading, callApi } = useApi();


    async function CreateHouseHold (n : string, t : string) {
        return await callApi(
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

    async function JoinHouseHold (ac : string, t : string) {
        return await callApi(
            urlBase + "api/JoinHouseHold", 
            {
                method: "POST",
                body: {
                    accessCode : ac,
                },
                token: t,
            }
        );
    }

    async function AddPeopleToHouseHold(
        token: string,
        accessCode: string,
        role: HouseHoldRoleEnum | string,
        firstname?: string,
        lastname?: string,
        email?: string
    ) {
        return await callApi(
            urlBase + "api/AddPeopleHouseHold",
            {
            method: "POST",
            body: {
                name: firstname ?? null,
                lastName: lastname ?? null,
                email: email ?? null,
                role : role,
                accessCode : accessCode,
            },
            token,
            }
        );
    }

    async function GetHouseHold (t : string) {
        return await callApi(
            urlBase + "api/GetHouseHold", 
            {
                method: "GET",
                token: t,
            }
        );
    }

    async function GetHouseHoldMembers (t : string, ac : string) {
        return await callApi(
            urlBase + "api/GetHouseHoldMembers",
            {
                method: "POST",
                body : {
                    accessCode : ac,
                },
                token: t,
            }
        );
    }

    async function CheckIsAdmin (t : string, ac : string) {
        return await callApi(
            urlBase + "api/CheckIsAdmin",
            {
                method: "POST",
                body : {
                    accessCode : ac
                },
                token: t,
            }
        );
    }

    async function DeletePeopleHouseHold(t : string, ac : string, id : number) {
        return await callApi(
            urlBase + `api/DeletePeopleHouseHold/${id}`,
            {
                method: "POST",
                body : {
                    accessCode : ac
                },
                token: t,
            }
        );
    }

    return {
        data,
        error,
        loading,
        CreateHouseHold,
        JoinHouseHold,
        GetHouseHoldMembers,
        GetHouseHold,
        AddPeopleToHouseHold,
        CheckIsAdmin,
        DeletePeopleHouseHold
    };
}
