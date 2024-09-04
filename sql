
CREATE TABLE `partidas` (
  `id` int(11) NOT NULL,
  `time_casa_id` int(11) NOT NULL,
  `time_visitante_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `local` varchar(255) DEFAULT NULL,
  `status` enum('Agendada','Em Andamento','Finalizada') DEFAULT 'Agendada',
  `criador_id` int(11) NOT NULL,
  `gols_casa` int(11) DEFAULT 0,
  `gols_visitante` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `times` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `capitao_id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `times_usuarios` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_id` int(11) NOT NULL,
  `position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_casa_id` (`time_casa_id`),
  ADD KEY `time_visitante_id` (`time_visitante_id`);


ALTER TABLE `times`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `capitao_id` (`capitao_id`);


ALTER TABLE `times_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`time_id`),
  ADD KEY `time_id` (`time_id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `partidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;


ALTER TABLE `times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;


ALTER TABLE `times_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;


ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`time_casa_id`) REFERENCES `times` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partidas_ibfk_2` FOREIGN KEY (`time_visitante_id`) REFERENCES `times` (`id`) ON DELETE CASCADE;

ALTER TABLE `times`
  ADD CONSTRAINT `times_ibfk_1` FOREIGN KEY (`capitao_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

ALTER TABLE `times_usuarios`
  ADD CONSTRAINT `times_usuarios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `times_usuarios_ibfk_2` FOREIGN KEY (`time_id`) REFERENCES `times` (`id`) ON DELETE CASCADE;
COMMIT;
