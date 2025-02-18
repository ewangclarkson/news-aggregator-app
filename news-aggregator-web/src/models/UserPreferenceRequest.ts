export interface UserPreferenceRequest {
    preferences: {
        sources?: string[];
        categories?: string[];
        authors?: string[];
    };
}