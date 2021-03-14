export interface Person {
  name: string;
  description: string | null;
}

export interface Artist extends Person {
  role: 'artist';
}
