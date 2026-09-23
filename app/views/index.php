<div class="container">
	<div class="chat-strike" style="background-image: url('{{ asset('img/backgrounds/background' . rand(1, $max_backgrounds) . '.png') }}');">
		<div class="chat-strike-overlay">
			<div class="chat-strike-left">
				<div class="chat-strike-online"></div>

				<div class="chat-strike-content"></div>

				<div class="chat-strike-actions">
					<div class="chat-strike-actions-message">
						<form onsubmit="event.preventDefault(); window.chatMessage(); this.children[0].value = '';">
							<input class="chat-input-element" type="text" placeholder="Enter a message..."/>
						</form>
					</div>

					<div class="chat-strike-actions-settings">
						<input class="chat-input-element" type="text" placeholder="Choose your name" onchange="localStorage.setItem('cl_name', this.value);"/>

						<select class="chat-input-element" title="Choose a team" onchange="localStorage.setItem('cl_team', this.value);">
							<option value="spec" selected>Spectator</option>
							<option value="t">Terrorists</option>
							<option value="ct">Counter-Terrorists</option>
						</select>
					</div>
				</div>
			</div>

			<div class="chat-strike-right">
				<div class="chat-strike-list"></div>
			</div>
		</div>
	</div>
</div>