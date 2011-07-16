<?php
	class TicketableBehavior extends ModelBehavior{
		/**
		 * Initiate behavior for the model using specified settings.
		 * Available settings:
		 *
		 * - timeout :: the date/time when the ticket will expire
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		function setup(&$Model, $settings = array()) {
			$default = array(
				'expires' => date('Y-m-d H:i:s', time() + (24 * 60 * 60))
			);

			if (!isset($this->__settings[$Model->alias])) {
				$this->__settings[$Model->alias] = $default;
			}

			$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], ife(is_array($settings), $settings, array()));
			$this->Ticket = ClassRegistry::init('Management.Ticket');
		}

		/**
		 * Create a new ticket.
		 *
		 * Create a new ticket by providing the data to be stored in the ticket.
		 * If no information is passed false is returned, or the ticket id is returned.
		 *
		 * @param string $info the information to save. will be serialized so arrays are fine
		 */
		function createTicket(&$Model, $info = null){
			$this->cleanup();

			if (!$info){
				return false;
			}

			$data['Ticket']['data']	= serialize($info);
			$data['Ticket']['expires'] = $this->__settings[$Model->alias]['expires'];

			$this->Ticket->create();
			$return = $this->Ticket->save($data);
			if ($this->Ticket->id > 0){
				return $this->Ticket->id;
			}

			return false;
		}

		/**
		 * Get a ticket.
		 *
		 * If something is found return the data that was saved or return false
		 * if there is something wrong.
		 *
		 * @param string $ticket the ticket uuid
		 */
		function getTicket(&$Model, $ticket = null){
			$this->cleanup();
			if (!$ticket){
				return false;
			}

			$data = $this->Ticket->find(
				'first',
				array(
					'conditions' => array(
						'Ticket.id' => $ticket
					)
				)
			);

			if (isset($data['Ticket']) && is_array($data['Ticket'])){
				if($this->Ticket->delete($ticket)){
					return unserialize($data['Ticket']['data']);
				}
			}

			return false;
		}

		/**
		 * Remove old tickets
		 *
		 * When things are done, remove old tickets that have expired.
		 */
		function cleanup(){
			$this->Ticket->deleteAll(
				array(
					'Ticket.expires < ' => date('Y-m-d H:i:s')
				)
			);
		}
	}
?>