export interface User {
  name: string;
  created: Date;
  updated: Date;
}

export interface Me extends User {
  email: string;
}

export interface AuthToken {
  token: string;
}
