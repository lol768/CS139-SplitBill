CREATE TABLE users(
  user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR NOT NULL,
  email VARCHAR NOT NULL,
  password VARCHAR, /* bcrypt */
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  its_username VARCHAR,
  active INTEGER NOT NULL DEFAULT 0,
  has_avatar INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE groups(
  group_id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR NOT NULL,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  open INTEGER DEFAULT 0,
  secret INTEGER DEFAULT 0
);

CREATE TABLE users_groups(
  relation_id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id UNSIGNED INTEGER NOT NULL,
  group_id UNSIGNED INTEGER NOT NULL,
  role VARCHAR NOT NULL,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(user_id),
  FOREIGN KEY(group_id) REFERENCES groups(group_id)
);

CREATE TABLE bills(
  bill_id INTEGER PRIMARY KEY AUTOINCREMENT,
  amount INTEGER NOT NULL, /* Deliberate! */
  description VARCHAR NOT NULL,
  user_id UNSIGNED INTEGER NOT NULL,
  group_id UNSIGNED INTEGER NOT NULL,
  company VARCHAR NOT NULL,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(user_id),
  FOREIGN KEY(group_id) REFERENCES groups(group_id)
);

CREATE TABLE payments(
  payment_id INTEGER PRIMARY KEY AUTOINCREMENT,
  amount INTEGER NOT NULL, /* Deliberate! */
  bill_id INTEGER NOT NULL,
  user_id UNSIGNED INTEGER NOT NULL,
  completed INTEGER NOT NULL DEFAULT 0,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  FOREIGN KEY(bill_id) REFERENCES bills(bill_id),
  FOREIGN KEY(user_id) REFERENCES users(user_id)
);

CREATE TABLE email_confirmations(
  user_id INTEGER NOT NULL,
  token VARCHAR NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(user_id)
);
