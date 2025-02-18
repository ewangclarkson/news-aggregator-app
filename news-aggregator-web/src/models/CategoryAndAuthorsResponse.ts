import {UserPreferenceResponse} from "./UserPreferenceResponse";

export interface CategoryAndAuthorsResponse {
    categories: string[];
    authors: string[];
    preferences: UserPreferenceResponse
}