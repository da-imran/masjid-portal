export interface AgencyLink {
    id: number;
    name: string;
    image: string;
    href: string;
}

export const agencyLinks: AgencyLink[] = [
    {
        id: 1,
        name: 'Main PP',
        image: '/images/main-pp.png',
        href: 'https://www.mainpp.gov.my/',
    },
];
